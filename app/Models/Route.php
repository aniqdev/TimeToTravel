<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'preview_url',
        'name',
        'description',
        'price',
        'old_price',
        'length',
        'duration',
        'transport', // ['undefined', 'walk', 'car', 'public_transport']
        'origin',
        'latitude',
        'longitude',
        'user_id',
        'city_id',
        'line_points',
        'rating',
        'views',
        'options',
    ];

    protected $guarded = [
        'status' // ['undefined', 'pending', 'accepted', 'declined']
    ];

    protected $hidden = [
        'status',
        'user_id',
        'city_id',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'points',
        'markers',
        // 'options',
    ];

    protected $appends = [
        'paid',
        'origin',
        'preview',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function sights()
    {
        if (request()->is('api/*')) {
            return $this->hasMany(Sight::class)->where(function($db)
            {
                $db->whereRaw("name <> '€€' OR name IS null");
            })->orderBy('priority');
        }else{
            return $this->hasMany(Sight::class)->orderBy('priority');
        }
    }

    public function markers()
    {
        return $this->hasMany(Sight::class)->where(function($db)
        {
            $db->whereRaw("name <> '€€' OR name IS null");
        })->orderBy('priority');
    }

    public function points()
    {
        return $this->hasMany(Sight::class)->orderBy('priority');
    }

    public function video()
    {
        return $this->hasMany(RouteVideo::class);
    }

    public function audio()
    {
        return $this->hasMany(RouteAudio::class);
    }

    public function images()
    {
        return $this->hasMany(RouteImage::class)->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(RouteReview::class)->limit(200);
    }

    public function getOptionsAttribute()
    {
        return json_decode($this->attributes['options']);
    }

    public function getLinePointsAttribute()
    {
        // $line_points = [];
        // foreach ($this->points as $sight) {
        //     $line_points[] = [ $sight->longitude, $sight->latitude ];
        // }
        // return $line_points;
        
        if(!$this->attributes['line_points']) return [];
        $line_points = json_decode($this->attributes['line_points']);
        return $line_points ?? [];
    }

    public function getPaidAttribute()
    {
        return 0;
    }

    public function getOriginAttribute()
    {
        if (!empty($this->attributes['latitude']) && !empty($this->attributes['longitude'])) {
            return [
                'lat' => $this->attributes['latitude'],
                'lng' => $this->attributes['longitude'],
            ];
        }else{
            return [
                'lat' => 0,
                'lng' => 0,
            ];
        }
    }

    public function getPreviewUrlAttribute()
    {
        $preview_url = $this->attributes['preview_url'];
        if ($preview_url && strpos($preview_url, 'http') !== 0) {
            $preview_url = asset($preview_url);
        }
        return $preview_url;
    }

    public function getPreviewAttribute()
    {
        $preview_url = $this->attributes['preview_url'];
        if ($preview_url && strpos($preview_url, 'http') !== 0) {
            $preview_url = str_replace('route-images', 'route-images-previews', $preview_url);
            $preview_url = asset($preview_url);
        }
        return $preview_url;
    }

    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     // $array['paid'] = $this->paid;
    //     // $array['origin'] = $this->origin;
    //     return $array;
    // }

    public static function getRoutesByCity($city) {
        return \DB::table('cities')
            ->where('city_ENG', '=', $city)
            ->join('routes', 'cities.id', '=', 'routes.city_id')
            ->join('users', 'routes.user_id', '=', 'users.id')
            ->join('sights', 'sights.route_id', '=', 'routes.id')
            ->where('sights.priority', '=', '1')
            ->select(
                'routes.*',
                'sights.latitude',
                'sights.longitude',
                'users.name',
                'users.surname',
                'users.description as user_description'
            )
            ->get()->toArray();
    }

    public static function addCity($id, $city) {
        $route = Route::find($id);
        $route['city_id'] = $city;
        $route->save();
    }

    public static function addLength($id, $length) {
        $route = Route::find($id);
        $route['length'] = $length;
        $route->save();
    }

    public static function addOrigin($id) {
        $route = Route::find($id);
        if ($route->sights()->first()) {
            $longitude = $route->sights()->first()['longitude'];
            $latitude = $route->sights()->first()['latitude'];
            $route['origin'] = \DB::raw("ST_GeomFromText('POINT($longitude $latitude)')");
            $route->save();
        }
    }

    public static function getRoutesPrepare()
    {
        $routes = new Route;

        $routes = $routes->select('routes.*', 'route_user_info.is_favorite', 'route_user_info.is_viewed', 'route_user_info.is_downloaded');

        $routes = $routes->leftJoin('route_user_info', function($leftJoin)
        {
            $leftJoin->on('routes.id', '=', 'route_user_info.route_id')
                     ->where('route_user_info.user_id', '=', auth()->user()->id ?? null);
        });

        if ($sortBy = request('sortBy')) {
            $sortDir = request('sortDir');
            $sortBy = ['name'=>'name','rating'=>'rating','views'=>'views'][strtolower($sortBy)] ?? 'id';
            $sortDir = ['asc'=>'asc','desc'=>'desc'][strtolower($sortDir)] ?? 'asc';
            $routes->orderBy($sortBy, $sortDir);
        }

        $routes->limit(100); // wierd but no need to assigne

        return $routes;
    }

    public static function getRoutesInCity($city_id)
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('city_id', $city_id);

        return $routes->get()->nullToZero();
    }

    public static function findRoutesInCity($city_id, $text)
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('city_id', $city_id)
                         ->where('name', 'LIKE', '%'.$text.'%');

        return $routes->get()->nullToZero();
    }

    // logic of finding nearest routes need to be program
    public static function getNearestRoutes($latitude, $longitude)
    {
        $routes = self::getRoutesPrepare();

        return $routes->get()->nullToZero();
    }

    public static function getRoutesByAuthor($user_id)
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('routes.user_id', $user_id);

        return $routes->get()->nullToZero();
    }

    public static function findAuthorRoutes($user_id, $text)
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('routes.user_id', $user_id)
                         ->where('name', 'LIKE', '%'.$text.'%');

        return $routes->get()->nullToZero();
    }

    public static function getFavoriteRoutes()
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('is_favorite', '1');

        return $routes->get()->nullToZero();
    }

    public static function getViewedRoutes()
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('is_viewed', '1');

        return $routes->get()->nullToZero();
    }

    public static function getDownloadedRoutes()
    {
        $routes = self::getRoutesPrepare();

        $routes = $routes->where('is_downloaded', '1');

        return $routes->get()->nullToZero();
    }

}
