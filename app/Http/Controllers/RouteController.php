<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{City, Route, Sight, RouteImage, RouteAudio, RouteVideo, SightImage, SightAudio, SightVideo};
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$orderByRaw = session('routesOrder', 'created_at DESC');

		$limit = (int)session('routesPerPage', 10);

		$routes = Route::withTrashed()->with('markers.video', 'markers.audio', 'markers.images', 
							'audio', 'video', 'images', 'city', 'author', 'reviews')
				->orderByRaw($orderByRaw)
				->paginate($limit);

		return view('routes.index', [ 'routes' => $routes ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$cities = City::select('id', 'city as title', 'latitude', 'longitude')
			->where('active', '1')
			->orderBy('id')->get();
		return view('routes.create', ['cities' => $cities]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$route = Route::create([
			'name' => $request->get('name'),
			'description' => $request->get('description'),
			'user_id' => auth()->user()->id,
			'city_id' => $request->get('city_id'),
		]);

		return [
			'status' => 'ok',
			'route' => $route,
			'request' => $request->all(),
			'location' => route('routes.edit', $route->id),
		];
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$route = Route::findOrFail($id);
// dd($route->options->how_search_places);
		$cities = City::select('id', 'city as title', 'latitude', 'longitude')
			->where('active', '1')
			->orderBy('id')->get();
		return view('routes.edit', [
			'cities' => $cities,
			'route' => $route,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $route_id)
	{

		$validator = validator($request->all(), [
			'name' => 'required',
			'description' => 'required',
			'price' => 'required',
			'old_price' => 'required',
			'latitude' => 'required',
			'longitude' => 'required',
			'length' => 'required',
			'duration' => 'required',
			'transport' => 'required',
			'language' => 'required',
			'line_points' => '',
			'city_id' => '',
		],[
			'name.required' => 'Укажите название маршрута',
			'name.description' => 'Укажите описание маршрута',
			'length.required' => 'Укажите длину маршрута',
			'duration.required' => 'Укажите продолжительность маршрута',
		]);

		if($validator->fails()){
			return [
				'status' => 'error',
				'message' => implode('<br>', $validator->messages()->all()),
				'errors' => $validator->messages()->all(),
			];
		}

		$route_data = $validator->validated();

		$route_data['user_id'] = auth()->user()->id; // author

		$route_data['line_points'] = json_encode(json_decode(request('line_points'))); // to remove spaces

		$route_data['options'] = json_encode($request->options);

		if($request->hasfile('preview_url'))
		{
			$path = $request->file('preview_url')->store('route-images', 'public');
			$route_data['preview_url'] = '/storage/'.$path;
		}

		$route = Route::find($route_id);

		if(!$route) return [
			'status' => 'error',
			'message' => 'Route not found',
		];

		$route->update($route_data);

		if ($request->get('sights')) {
			// $route->saveSights();
			foreach ($request->get('sights') as $sight_key => $sight_data) {
				$sight_data['route_id'] = $route->id;

				$sight = Sight::find($sight_data['id']);

				if ($sight) {
					$sight->update($sight_data);
				}else{
					$sight = Sight::create($sight_data);
				}
			}

		}

		return [
			'status' => 'ok',
			'message' => "Маршрут обновлен (id {$route->id})",
			'$route_data' => $route_data,
			'$route' => $route,
			'$request' => $request->all(),
			'sights' => $request->get('sights'),
			'line_points' => request('line_points'),
			'preview_url' => isset($route_data['preview_url']) ? asset($route_data['preview_url']) : 0,
		];
	}


	public function updateRouteSightFilesOrder(Request $request)
	{

		if (request('object') && request('object') === 'route') {
			$images = RouteImage::where('route_id', request('objectId'))->get();
		}
		if (request('object') && request('object') === 'sight') {
			$images = SightImage::where('sight_id', request('objectId'))->get();
		}

		$list = array_flip(request('list'));
		foreach ($images as $key => $image) {
			if (isset($list[$image->id])) {
				$image->update(['order' => $list[$image->id]]);
			}	
		}

		return [
			'$images' => $images,
		];
	}


	public function updateRouteSightFiles(Request $request)
	{
		$rules = [
            'images.*' => ['mimes:jpg,png'],
            'audio.*' => ['mimes:audio/mpeg,mpga,mp3,wav'],
            // 'video.*' => 'mimetypes:video/avi,video/mpeg,video/quicktime',
        ];

        $messages = [
        	'images.*.mimes' => 'Поле "Фото" должно быть файлом одного из типов: jpg,png.',
        	'audio.*.mimes' => 'Поле "Аудио" должно быть файлом одного из типов: mp3,wav.',
        ];

        $validator = validator($request->all(), $rules, $messages);

        if($validator->fails()){
            return [
                'status' => 'error',
                'message' => implode('<br>', $validator->messages()->all()),
                'errors' => $validator->messages()->all(),
            ];
        }

		if ($request->has('route_id')) {
			return $this->updateRouteFiles($request);
		}
		if ($request->has('sight_id')) {
			return $this->updateSightFiles($request);
		}
	}


	public function updateRouteFiles(Request $request)
	{

		if($request->hasfile('images'))
		{
			if(count($request->file('images')) > 8)	return [
				'status' => 'error',
				'message' => 'Максимум 8 изображений!',
			];

			foreach($request->file('images') as $file)
			{
				$path = $file->store('route-images', 'public');
				if ($path) {
					$routeImage = RouteImage::create([
						'route_id' => (int)$request->get('route_id'),
						'title' => '',
						'url' => '/storage/'.$path,
					]);
				}
			}
		}

		if($request->hasfile('audio'))
		{
			if(count($request->file('audio')) > 8)	return [
				'status' => 'error',
				'message' => 'Максимум 8!',
			];

			foreach($request->file('audio') as $file)
			{
				$path = $file->store('route-audio', 'public');
				if ($path) {
					$routeImage = RouteAudio::create([
						'route_id' => (int)$request->get('route_id'),
						'title' => '',
						'url' => '/storage/'.$path,
					]);
				}
			}
		}

		if ($request->has('video')) {
			foreach ($request->get('video') as $key => $video_url) {
				if(trim($video_url)) RouteVideo::create([
					'route_id' => (int)$request->get('route_id'),
					'title' => '',
					'url' => trim($video_url),
				]);
			}
		}

		$route = Route::with('audio', 'video', 'images')->find($request->get('route_id'));

		if (!$route) {
			return [
				'status' => 'error',
				'message' => 'Not found',
			];
		}

		return [
			'status' => 'ok',
			'files' => $_FILES,
			'$request' => $request->all(),
			'view' => view('routes.blocks.show-object-files', ['object' => $route])->render(),
		];
	}


	public function updateImageTitle(Request $request)
	{
		if (request('object_type') === 'route') {
			$object = RouteImage::find($request->image_id);
		}

		if (request('object_type') === 'sight') {
			$object = SightImage::find($request->image_id);
		}

		$object->title = $request->title;
		
		return [
			'status' => $object->save() ? 'ok' : 'error',
			'title' => $request->title,
		];
	}


	public function updateAudioTitle(Request $request)
	{
		if (request('object_type') === 'route') {
			$object = RouteAudio::find($request->audio_id);
		}

		if (request('object_type') === 'sight') {
			$object = SightAudio::find($request->audio_id);
		}

		$object->title = $request->title;
		
		return [
			'status' => $object->save() ? 'ok' : 'error',
			'title' => $request->title,
		];
	}


	public function updateSightFiles(Request $request)
	{
		if($request->hasfile('images'))
		{
			if(count($request->file('images')) > 8)	return [
				'status' => 'error',
				'message' => 'Максимум 8 изображений!',
			];

			foreach($request->file('images') as $file)
			{
				$path = $file->store('sight-images', 'public');
				if ($path) {
					$sightImage = SightImage::create([
						'sight_id' => (int)$request->get('sight_id'),
						'title' => '',
						'url' => '/storage/'.$path,
					]);
				}
			}
		}

		if($request->hasfile('audio'))
		{
			if(count($request->file('audio')) > 8)	return [
				'status' => 'error',
				'message' => 'Максимум 8!',
			];

			foreach($request->file('audio') as $file)
			{
				$path = $file->store('sight-audio', 'public');
				if ($path) {
					$sightAudio = SightAudio::create([
						'sight_id' => (int)$request->get('sight_id'),
						'title' => '',
						'url' => '/storage/'.$path,
					]);
				}
			}
		}

		if ($request->has('video')) {
			foreach ($request->get('video') as $key => $video_url) {
				if(trim($video_url)) SightVideo::create([
					'sight_id' => (int)$request->get('sight_id'),
					'title' => '',
					'url' => trim($video_url),
				]);
			}
		}

		$sight = Sight::with('audio', 'video', 'images')->find($request->get('sight_id'));

		if (!$sight) {
			return [
				'status' => 'error',
				'message' => 'Not found',
			];
		}

		return [
			'status' => 'ok',
			'$request' => $request->all(),
			'view' => view('routes.blocks.show-object-files', ['object' => $sight, 'sight' => $sight])->render(),
		];
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($route_id)
	{
		$route = Route::with('sights.audio', 'sights.images', 'images', 'audio')->find($route_id);

		if(!$route) return [
			'status' => 'error',
			'message' => 'Wrong route',
		];

		$deleted = $route->delete();

		return [
			'status' => $deleted ? 'ok' : 'error',
			'route' => $route,
			'deleted' => $deleted,
		];
	}


	public function restoreRoute(Request $request)
	{
		if (!$request->route_id) return [
			'status' => 'error',
			'message' => 'Error!',
		];

		$restored = Route::withTrashed()->find($request->route_id)->restore();

		return [
			'status' => $restored ? 'ok' : 'error',
			'restored' => $restored,
		];
	}


	public function forceDestroy($route_id)
	{
		$route = Route::with('sights.audio', 'sights.images', 'images', 'audio')->find($route_id);

		if(!$route) return [
			'status' => 'error',
			'message' => 'Wrong route',
		];

		// Db::table('route_user_info')
		// 		->where('route_id', $route->id)
		// 		->delete();

		// delete images
		// foreach ($route->images as $route_image) {
		// 	$route_image_url = $route_image->url;
		// }

		// delete audio
		// foreach ($route->audio as $route_audio) {
		// 	$route_audio_url = $route_audio->url;
		// }

		$route->reviews()->delete();

		// sights
		foreach ($route->points as $sight) {
			break; // deleting of all related object should be at $route->forceDelete();
			// sight images
			foreach ($sight->images as $sight_image) {
				// $sight_image->url
				$sight_image->delete();
			}
			// sight audio
			foreach ($sight->audio as $sight_audio) {
				// $sight_audio->url
				$sight_audio->delete();
			}
			// sight video
			foreach ($sight->video as $sight_video) {
				// $sight_video->url
				$sight_video->delete();
			}
			$sight->delete();
		}

		$deleted = $route->delete();

		return [
			'status' => $deleted ? 'ok' : 'error',
			'route' => $route,
			'deleted' => $deleted,
			// 'sights' => Sight::where('route_id', $route_id)->with('images')->get()->images,
		];
	}


	public function removeSight(Request $request)
	{
		$sight_id = $request->get('sight_id');

		$sight = Sight::find($sight_id);

		if (!$sight) return [
			'status' => 'error',
		];

		return [
			'status' => $sight->delete() ? 'ok' : 'error',
		];
	}


	public function createSight(Request $request)
	{
		$route_id = $request->get('route_id');

		if (!$route_id) return [
			'status' => 'error',
		];

		$sight = Sight::create([
			'route_id' => $route_id,
			'name' => request('name'),
			'description' => request('description'),
			'latitude' => request('latitude'),
			'longitude' => request('longitude'),
		]);

		return [
			'status' => $sight ? 'ok' : 'error',
			'request' => $request->all(),
			'sight_id' => $sight->id ?? 0,
		];
	}


	public function removeObjectFile(Request $request){

		$object_type = $request->get('object_type');

		if (class_exists('\\App\\Models\\'.$object_type)) {
			if ($object = ('\\App\\Models\\'.$object_type)::find(request('object_id'))) {
				$path = public_path($object->urlAttribute());
				if (file_exists($path) && strpos($path, '/storage/') !== false){
					unlink($path);
					$object->delete();
					return [
						'status' => 'ok',
						'ss' => $object->urlAttribute(),
					];
				}
				if ($object_type === 'RouteVideo' || $object_type === 'SightVideo') {
					$object->delete();
					return [
						'status' => 'ok',
						'ss' => $object->urlAttribute(),
					];
				}
			}
		}

		return [
			'status' => 'error',
		];

	}


	public function searchAjax(Request $request)
	{
		$query = $request->get('query');

		if (!trim($query)) return [
			'status' => 'error',
		];

		DB::enableQueryLog();
		$search_list_html = '';

		$routes = Route::where('name', 'ilike', '%'.$query.'%')->limit(10)->get(); // Warning "ilike"

		foreach ($routes as $route) {
			$search_list_html .= '<li class="result-item" title="'.$route->name.'"><a href="/routes/'.$route->id.'/edit">'.$route->name.'</a></li>';
		}

		if($search_list_html) {
			$search_list_html .= '<li class="result-item more-results" title="More results"><a href="'.route('routes.search').'?query='.urlencode($query).'">More results</a></li>';
		}else{
			$search_list_html = '<li>No results</li>';
		}

		return [
			'status' => 'ok',
			'routes' => $routes,
			'search_list_html' => $search_list_html,
			'results_count' => count($routes) + 1,
			'sql' => DB::getQueryLog(),
		];
	}


	public function search(Request $request)
	{
		$query = $request->get('query');

		if(!trim($query)) return view('routes.search', [
			'routes' => [],
			'message' => 'Введите поисковый запрос!',
		]);

		$routes = Route::where('name', 'ilike', '%'.$query.'%')->with('author')->limit(30)->get(); // Warning "ilike"

		return view('routes.search', [
			'routes' => $routes,
			'message' => 'Вы искали "' . request('query') . '". Показано ' . count($routes) . ' результатов',
		]);
	}

}
