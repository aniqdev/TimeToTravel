<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\{Route,Sight,City,User,RouteReview};


class ApiV1Controller extends Controller
{
	private $routeSelect = ['id', 'name', 'description', 'price', 'old_price', 'preview_url', 'latitude', 'longitude'];

	public function androidLogin(Request $request)
	{
		$device_id = trim($request->get('device_id')) ?? trim($request->get('deviceId'));

		if (!$device_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'device_id is required',
			], 400);
		}

		$user = User::getByEmail($device_id);

		if (!$user){
			$user = User::create([
				'name' => 'user',
				'surname' => 'user',
				'email' => $device_id,
				'password' => bcrypt($device_id),
				'avatar' => '/images/user-icon.png',
			]);
		}

		return $this->json([
			'status' => 'ok',
			'token' => $user->createToken($device_id)->plainTextToken,
			'user' => [
				'id' => $user->id,
				'name' => $user->name,
			],
		]);
	}


	// public function androidUserInfo(Request $request)
	// {
	// 	return auth()->user();
	// }


	/*
	Метод: /getCities
	GET
	Список всех городов
	Возвращаемый json: поля по таблице
	*/
	public function getCities(Request $request)
	{
		$limit = $request->get('limit') ?? 1000;

		$offset = $request->get('offset') ?? 0;

		$lang = $request->get('lang') ?? 'ru';

		$cities = City::where('active', '1')->limit($limit)->offset($offset)->select('id', 'city as title', 'country')->get();

		// json($data = [], $status = 200, array $headers = [], $options = 0)
		return $this->json([
			'status' => 'ok',
			'count' => count($cities),
			'limit' => $limit,
			'offset' => $offset,
			'total' => City::where('active', '1')->count(),
			'lang' => $lang,
			'cities' => $cities,
		]);
	}

	/*
	Метод: /findCities?text=var1
	GET
	Поиск городов, выполняет поиск на стороне сервера по названию города,
	Параметры:
	text - искомый текст
	Возвращаемый json: поля по таблице
	*/
	public function findCities(Request $request)
	{
		if($request->sql) DB::enableQueryLog();

		$search_query = $request->get('text');

		if (!$search_query) {
			return $this->json([
				'status' => 'error',
				'message' => 'Please enter "text" parameter',
			], 400);
		}

		$limit = $request->get('limit') ?? 1000;

		$offset = $request->get('offset') ?? 0;

		$lang = $request->get('lang') ?? 'ru';

		// Warning ILIKE just for postgres
		$cities = City::where('city', 'ILIKE', '%'.$search_query.'%')->where('active', '1')
						->limit($limit)->offset($offset)
						->select('id', 'city as title', 'country')->get();

		// json($data = [], $status = 200, array $headers = [], $options = 0)
		return $this->json([
			'status' => 'ok',
			'count' => count($cities),
			'limit' => $limit,
			'offset' => $offset,
			'total' => City::where('active', '1')->count(),
			'lang' => $lang,
			'cities' => $cities,
			'sql' => DB::getQueryLog(),
		]);
	}

	/*
	Метод: /getRoute?routeId=var1&userId=var2
	GET
	Метод возвращает маршрут по указанному routeId, и возвращает полную информацию по маршруту
	userId - параметр опциональный, если он указан, то следует отметить в базе, что пользователь просмотрел маршрут, тамблица viewed
	Возвращаемый json: [ {...}, {...} ]
	*/
	public function getRoute(Request $request)
	{
		// if($request->sql) DB::enableQueryLog();

		$route_id = $request->get('routeId');

		if (!$route_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required.',
			], 404);
		}

		$user = $this->sanctumLogin($request);

		$route = new Route;

		if (@$user) {

			$views = $this->markRouteAsViewed($user->id, $route_id);

			$route = $route->select('routes.*', 'route_user_info.is_favorite', 'route_user_info.is_viewed', 'route_user_info.is_downloaded')
						->leftJoin('route_user_info', function($leftJoin)
					        {
					            $leftJoin->on('routes.id', '=', 'route_user_info.route_id')
					                     ->where('route_user_info.user_id', '=', auth()->user()->id);
					        });
		}else{
			$views = false;
		}

		$route = $route->where('routes.id', $route_id)
						->with($this->getRouteWith())->first();

		if ($views) {
			$route->update(['views' => $views]);
		}

		return $this->json([
			'sql' => DB::getQueryLog(),
			'status' => 'ok',
			'lang' => 'ru',
			'route' => $route,
		]);
	}

	/*
	Метод: /getRoutesInCity?cityId=var1&sortby=var2&sortdir=var3
	GET
	Возвращает все маршруты по id города
	Параметры:
	id - id города
	sortby - поле по которому выполяется сортировка
	sortdir - направление сортировки: asc/desc
	Возвращаемый json: [ {...}, {...} ]
	Каждый элемент, примерно так, ниже пояснения
	Не согласовано, согласовывайте
	Это версия light, по сути здесь нужно:
	* превью
	* название
	* цена
	*/
	public function getRoutesInCity(Request $request)
	{
		$rules = [
			'cityId' => 'required|integer',
		];

		$messages = [
			'cityId.required' => 'cityId is required',
		];

		$validator = validator($request->all(), $rules, $messages);

		if($validator->fails()){
			return $this->json([
				'status' => 'error',
				'message' => implode(', ', $validator->messages()->all()),
			], 400);
		}

		$data = $validator->validated(); // to use auth()->user() / null if login failed

		$this->sanctumLogin($request);

		$routes = Route::getRoutesInCity($data['cityId']);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			// 'user' => $user,
			'routes' => $routes,
		]);
	}

	/*
	Метод: /findRoutesInCity?cityId=var1&text=var2&sortby=var3&sortdir=var4
	GET
	Ищет моршруты по указанному cityId и тексту, судя по всему по названию
	Параметры - как в предыдущем, с сортировкой
	Возвращаемый json: [ {...}, {...} ] // как выше
	*/
	public function findRoutesInCity(Request $request)
	{
		$rules = [
			'cityId' => 'required|integer',
			'text' => '',
		];

		$validator = validator($request->all(), $rules);

		if($validator->fails()){
			return $this->json([
				'status' => 'error',
				'message' => implode(', ', $validator->messages()->all()),
			], 400);
		}

		$data = $validator->validated();

		$this->sanctumLogin($request);

		$routes = Route::findRoutesInCity($data['cityId'], $data['text']);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			'routes' => $routes,
		]);
	}

	/*
	Метод: /getNearestRoutes?radius=var1
	GET
	Запрашивает ближайшие к пользователю маршруты в некотором радиусе
	radius - радиус в метрах или километрах - на ваш выбор, лучше к километрах, наверное
	наверное, должен быть радиус по умолчанию, без указания параметра, допустим 5 или 10 км
	Возвращаемый json: [ {...}, {...} ] // как выше
	*/
	public function getNearestRoutes(Request $request)
	{
		$rules = [
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
		];

		$messages = [
			// 'latitude.required' => 'latitude is required',
			// 'longitude.required' => 'longitude is required',
		];

		$validator = validator($request->all(), $rules, $messages);

		if($validator->fails()){
			return $this->json([
				'status' => 'error',
				'message' => implode(', ', $validator->messages()->all()),
			], 400);
		} 

		$routes = Route::select($this->routeSelect)->limit(10)->inRandomOrder()->get();

		$data = $validator->validated();

		$this->sanctumLogin($request);

		$routes = Route::getNearestRoutes($data['latitude'], $data['longitude']);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			'routes' => $routes,
		]);
	}

	/*
	Метод: /getRoutesByAuthor?authorId=var1&sortby=var2&sortdir=var3
	GET
	Запрашивает маршруты по указанному автору, по его id
	С сортировкой.
	Возвращаемый json: [ {...}, {...} ] // как выше
	*/
	public function getRoutesByAuthor(Request $request)
	{
		$rules = [
			'authorId' => 'required|integer',
		];

		$validator = validator($request->all(), $rules);

		if($validator->fails()){
			return $this->json([
				'status' => 'error',
				'message' => implode(', ', $validator->messages()->all()),
			], 400);
		} 

		$data = $validator->validated();

		$this->sanctumLogin($request);

		$routes = Route::getRoutesByAuthor($data['authorId']);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			'routes' => $routes,
		]);
	}

	/*
	Метод: /findAuthorRoutes?authorId=var1&text=var2&sortby=var2&sortdir=var3
	GET
	Ищет моршруты по указанному authorId и тексту, судя по всему по названию
	Также указываются параметры сортировки
	Возвращаемый json: [ {...}, {...} ] // как выше
	*/
	public function findAuthorRoutes(Request $request)
	{
		$rules = [
			'authorId' => 'required|integer',
			'text' => '',
		];

		$validator = validator($request->all(), $rules);

		if($validator->fails()){
			return $this->json([
				'status' => 'error',
				'message' => implode(', ', $validator->messages()->all()),
			], 400);
		}

		$data = $validator->validated();

		$this->sanctumLogin($request);

		$routes = Route::findAuthorRoutes($data['authorId'], $data['text']);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			'routes' => $routes,
		]);
	}

	/*
	Метод: /getAuthor?authorId=var1
	GET
	Возвращает данные об авторе из таблицы users
	Обязательно должен быть description
	Вообще, можно не делать этот метод, и всё по автору вернуть при запросе маршрута, а может и пригодится отдельный метод
	На ваше усмотрение.
	Для экскурсий по автору уже есть отдельные методы, т.е. их логично вызывать отдельно через него.
	Возвращаемый json: поля из таблицы users
	*/
	public function getAuthor(Request $request)
	{
		$user_id = (int)$request->authorId;

		if (!$user_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'authorId is required.',
			], 400);
		}

		$user = User::find($user_id);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'User not found.',
			], 404);
		}

		$routes = Route::where('user_id', $user_id)->get($this->routeSelect);

		return $this->json([
			'status' => 'ok',
			'lang' => 'ru',
			'author' => $user,
			'routes' => $routes,
		]);
	}

	/*
	Метод: /addFavorite?userId=var1&routeId=var2
	GET или POST
	Добавляет маршрут в избранные
	Возвращаемый json: коды успешно/не успешно
	*/
	public function addFavorite(Request $request)
	{
		$user = $request->user();
		$route_id = (int)$request->routeId;

		if (!$route_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required.',
			], 404);
		}

		$route_user_info = Db::table('route_user_info')
								->where('route_id', $route_id)
								->where('user_id', $user->id)
								->first();

		if($route_user_info){
			Db::table('route_user_info')
				->where('route_id', $route_id)
				->where('user_id', $user->id)
				->update(['is_favorite' => 1]);
		}else{
			Db::table('route_user_info')
				->insert([
					'route_id' => $route_id,
					'user_id' => $user->id,
					'is_favorite' => 1,
				]);
		}

		return [
			'status' => 'ok',
			'user' => $user,
			'routeId' => $request->routeId,
		];
	}

	/*
	Метод: /removeFavorite?userId=var1&routeId=var2
	GET или POST
	Удаляет маршрут из избарнных
	Возвращаемый json: коды успешно/не успешно
	*/
	public function removeFavorite(Request $request)
	{
		$user = $request->user();
		$route_id = (int)$request->routeId;

		if (!$route_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required.',
			], 400);
		}

		Db::table('route_user_info')
			->where('route_id', $route_id)
			->where('user_id', $user->id)
			->update(['is_favorite' => 0]);

		return [
			'status' => 'ok',
			'user' => $user,
			'routeId' => $request->routeId,
		];
	}

	/*
	Метод: /getFavorites?userId=var1
	GET
	Запрашивает все избранные маршруты пользователя
	Вот тут, думаю, сортировка не нужна
	Отсортирую у себя, наверное по дате добавления, в таблице favorites должно быть поле added_at
	Возвращаемый json: как в методе выше c маршрутом
	*/
	public function getFavorites(Request $request)
	{
		$user = $this->sanctumLogin($request);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'Unauthenticated.',
			], 401);
		}

		if($request->sql) DB::enableQueryLog();

		$routes = Route::getFavoriteRoutes();

		return $this->json([
			'status' => 'ok',
			'user' => $user,
			'routes' => $routes,
			// 'sql' => DB::getQueryLog(),
		]);
	}

	/*
	Метод: /getViewed?userId=var1
	GET
	Запрашивает все маршруты, просмотренные пользователем
	Т.е. должна быть таблица viewed и там должно быть поле viewed_at
	Добавляться и обновляться запись должна на стороне сервера при запросе конкретного маршрута
	Возвращаемый json: как в методе выше с маршрутом
	*/
	public function getViewed(Request $request)
	{
		$user = $this->sanctumLogin($request);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'Unauthenticated.',
			], 401);
		}

		if($request->sql) DB::enableQueryLog();

		$routes = Route::getViewedRoutes();

		return $this->json([
			'status' => 'ok',
			'user' => $user,
			'routes' => $routes,
			// 'sql' => DB::getQueryLog(),
		]);
	}

	/*
	Метод: /getDownloaded?userId=var1
	GET
	Запрашивает все маршруты, скаченные пользователем
	Т.е. должна быть таблица downloaded, и в ней должно быть поле downloaded_at
	Добавлять и обновляться запись должна на стороне сервера при нажатии кнопки Скачать
	Возвращаемый json: как в методе выше с маршрутом
	*/
	public function getDownloaded(Request $request)
	{
		$user = $this->sanctumLogin($request);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'Unauthenticated.',
			], 401);
		}

		if($request->sql) DB::enableQueryLog();

		$routes = Route::getDownloadedRoutes();

		return $this->json([
			'status' => 'ok',
			'user' => $user,
			'routes' => $routes,
			// 'sql' => DB::getQueryLog(),
		]);
	}

	/*
	Метод: /downloadRoute?userId=var1&routeId=var2
	GET
	Я не совсем понимаю, что означает "Скачать" и чем оно отличается от сохранения в избранных
	Что именно должно скачиваться, для меня - открытый вопрос
	Однако, мне ясно, что при вызове этого метода, на сервере должна добавлять/обновляться запись в базе downloaded,
	чтобы далее можно было просмотреть все скаченные маршруты :)
	Возвращаемый json: как в методе выше с маршрутом
	*/
	public function downloadRoute(Request $request)
	{
		$user = $request->user();
		$route_id = (int)$request->routeId;

		if (!$route_id) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required.',
			], 404);
		}

		$route_user_info = Db::table('route_user_info')
								->where('route_id', $route_id)
								->where('user_id', $user->id)
								->first();

		if($route_user_info){
			Db::table('route_user_info')
				->where('route_id', $route_id)
				->where('user_id', $user->id)
				->update(['is_downloaded' => 1]);
		}else{
			Db::table('route_user_info')
				->insert([
					'route_id' => $route_id,
					'user_id' => $user->id,
					'is_downloaded' => 1,
				]);
		}

		return [
			'status' => 'ok',
			'user' => $user,
			'routeId' => $request->routeId,
		];
	}


	public function markRouteAsViewed($user_id, $route_id)
	{
		if(!$user_id && $route_id) return;

		$route_user_info = Db::table('route_user_info')
								->where('route_id', $route_id)
								->where('user_id', $user_id)
								->first();

		if($route_user_info){
			Db::table('route_user_info')
				->where('route_id', $route_id)
				->where('user_id', $user_id)
				->update(['is_viewed' => 1]);
		}else{
			Db::table('route_user_info')
				->insert([
					'route_id' => $route_id,
					'user_id' => $user_id,
					'is_viewed' => 1,
				]);
		}

		// update route views
		$views = Db::table('route_user_info')
								->where('route_id', $route_id)
								->where('is_viewed', 1)
								->count();

		return $views;
	}

	/*
	Метод: /getUserReviews?userId=var1
	GET
	Метод возвращает все отзывы, оставленные пользователем, из таблицы reviews
	В методе должно быть поле added_at или changed_at, если они редактируются
	Возвращаемый json: поля из таблицы reviews
	*/
	public function getUserReviews(Request $request)
	{
		$user = $this->sanctumLogin($request);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'Unauthenticated.',
			], 401);
		}

		$reviews = RouteReview::where('user_id', $user->id)
			->with('route', 'author:id,name,surname')
			->get();

		return $this->json([
			'status' => 'ok',
			'user' => $user,
			'reviews' => $reviews,
		]);
	}

	/*
	метод getAuthorReviews
	на вход - id автора
	на выход - выборка. Выборка всех отзывов, по всем маршрутам этого автора. Сортировка в порядке убывания по дате.
	*/
	public function getRouteAuthorReviews(Request $request)
	{
		$routeAuthorId = (int)$request->routeAuthorId;

		if (!$routeAuthorId) return $this->json([
			'status' => 'error',
			'message' => 'Error',
		], 404);

		$reviews = RouteReview::with('route:id,name,preview_url,latitude,longitude', 'author:id,name,surname')
			->whereIn('route_id', function($query) use ($routeAuthorId){
			    $query->select('id')
					   ->from('routes')
					   ->where('user_id', $routeAuthorId);
			})
			->orderByDesc('created_at')->get();

		return $this->json([
			'status' => 'ok',
			// 'routes' => $routes,
			'reviews' => $reviews,
		]);
	}


	public function reviewExists(Request $request)
	{
		$user = $this->sanctumLogin($request);

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'Unauthenticated.',
			], 401);
		}

		if (!$request->routeId) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required',
			], 400);
		}

		$review = RouteReview::where('user_id', $user->id)
					->where('route_id', (int)$request->routeId)->first();

		return $this->json([
			'status' => 'ok',
			// 'user' => $user,
			'reviewExists' => (bool)$review,
		]);
	}


	/*
	Метод: /addReview
	POST
	добавляет отзыв
	JSON:
	{
	"userId": 10,
	"routeId": 1,
	"mark": 4,
	"header": "Незабываемая поездка",
	"text": "Незабываемая Поездка запомнилась на всю жизнь! Ещё не один раз обратимся сюда! Всё очень понравилось! И дети и я..."
	}
	*/
	public function addReview(Request $request)
	{
		$user = $request->user();

		if (!$user) {
			return $this->json([
				'status' => 'error',
				'message' => 'user not found.',
			], 404);
		}

		if (!$request->routeId) {
			return $this->json([
				'status' => 'error',
				'message' => 'routeId is required',
			], 400);
		}

		$user->name = $request->username ?? 'Гость';
		$user->save();

		$review = RouteReview::create([
            'route_id' => $request->routeId,
            'user_id' => $user->id,
            'mark' => $request->mark ?? 0,
            'title' => $request->title,
            'text' => $request->text,
            'approved' => 0,
		]);

		return $this->json([
			'status' => 'ok',
			'user' => $user,
			'review' => $review,
		]);
	}

	public function sanctumLogin(Request $request)
	{
		$token = $request->bearerToken() ?? $request->token;
		if(!$token) return null;

		$personalAccessToken = PersonalAccessToken::findToken($token);
		if(!$personalAccessToken) return null;

	    $user = $personalAccessToken->tokenable;
		if(!$user) return null;

	    auth()->login($user);
	    return $user;
	}

	protected function getRouteWith()
	{
		return [
			'sights.video',
			'sights.audio',
			'sights.images',
			'audio',
			'video',
			'images',
			'city',
			'author',
			'reviews',
			'reviews.author:id,name,surname',
		];
	}

	public function json($data, $http_code = 200)
	{
		return response()->json( $data, $http_code, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	}
}
