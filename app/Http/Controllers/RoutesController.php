<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Sight;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class RoutesController extends Controller
{
    public function info(int $id)
    {
        $route = Route::find($id);
        return response()->json($route);
    }

    public function sights(int $id)
    {
        $sights = Sight::getAllByRoute($id);
        return response()->json(array('sights' => $sights));
    }

    public function city(Request $request, int $limit, int $skip)
    {
        $data = $request->only('city', 'latitude', 'longitude');
        $routes = Route::getRoutesByCity($data['city']);

        $sinLat = sin(deg2rad(floatval($data['latitude'])));
        $cosLat = cos(deg2rad(floatval($data['latitude'])));

        usort($routes, function ($a, $b) use ($sinLat, $cosLat, $data) {
            return acos($sinLat * sin(deg2rad(floatval($a->latitude))) + $cosLat * cos(deg2rad(floatval($a->latitude)))
                * cos(deg2rad(floatval($data['longitude'])) - deg2rad(floatval($a->longitude)))) <
                acos($sinLat * sin(deg2rad(floatval($b->latitude))) + $cosLat * cos(deg2rad(floatval($b->latitude)))
                    * cos(deg2rad(floatval($data['longitude'])) - deg2rad(floatval($b->longitude))));
        });

        $routes = array_slice($routes, $skip, $limit);

        $routes = array_map(function ($item) {
            return (object) array(
                "id" => $item->id,
                "name" => $item->name,
                "description" => $item->description,
                "length" => $item->length,
                "transport" => $item->transport,
                "language" => $item->language,
                //"photo" => $item->photo,
                "author" => (object) array(
                    "name" => $item->name . ' ' . $item->surname,
                    "description" => $item->user_description
                ),
            );
        }, $routes);

        return response()->json($routes);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'mainImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $route_data = $request->only('name', 'description', 'transport');

        if ($request->hasFile('mainImage')) {
            $path = $request->file('mainImage')->storePublicly('routes', 'public');
            $route_data['photo'] = 'storage/' . $path;
        }

        if (Auth::check()) {
            $route_data['user_id'] = Auth::id();
        } else {
            return redirect()->route('login');
        }

        $route_data['city_id'] = 1; // id = 1 - Unknown

        $id = Route::create($route_data);
        session(['route_id' => $id]);
        return redirect()->route('sight');
    }

    function getLocationNames($lat, $long) {
        $get_API = "https://maps.googleapis.com/maps/api/geocode/json?latlng=";
        $get_API .= round($lat, 4).",".round($long, 4).'&language=ru&sensor=false&key='.env('GOOGLE_MAPS_API_KEY');        

        $jsonfile = file_get_contents($get_API);
        $jsonarray = json_decode($jsonfile);

        $city = 'Unknown';
        $country = 'Unknown';
    
        if (isset($jsonarray->results[1]->address_components)) {
            foreach($jsonarray->results[1]->address_components as $elem) {
                if ($elem->types[0] == 'locality') {
                    $city = $elem->long_name;
                }
                if ($elem->types[0] == 'country') {
                    $country = $elem->long_name;
                }
            }
        }
        return [$city, $country];
    }

    public function showSight(int $route_id, int $order, int $length)
    {
        $sight = Sight::getByRouteAndOrder($route_id, $order);
    
        if ($sight == null) {
            $longitude = "";
            $latitude = "";
            $name = "";
            $description = "";
            $images = [];
            $audio = "";
            $video = "";
        } else {
            $longitude = $sight['longitude'];
            $latitude = $sight['latitude'];
            $name = $sight['name'];
            $description = $sight['description'];
            $images = json_decode($sight['images']);
            $audio = json_decode($sight['audio']);
            $video = $sight['video'];
        }
        $key = env('GOOGLE_MAPS_API_KEY');

        return view("route/sight", compact('key', 'route_id', 'order', 'length', 'longitude', 'latitude', 'name', 'description', 'images', 'audio', 'video'));
    }

    function addCityToRoute(int $route_id) {
        $sight = Sight::getByRouteAndOrder($route_id, 1);

        $lat = $sight['latitude'];
        $long = $sight['longitude'];

        // TODO: CHANGE REVERSE GEOCODING
       /* list($city_name, $country_name) = $this->getLocationNames($lat, $long);
        $city = City::getByRUSName($city_name);
        if ($city == null) {
            $city['id'] = City::create($city_name, $country_name, $city_name, $country_name);
        }
        */
        $city['id'] = 1;

        Route::addCity($route_id, $city['id']);
        return;
    }

    function getDistance(int $route_id) {
        $sights = Sight::getAllByRoute($route_id);

        $distanceUrl = 'https://maps.googleapis.com/maps/api/directions/json?'.
        'origin='.$sights->first()['latitude'].','.$sights->first()['longitude'].
        '&destination='.$sights->last()['latitude'].','.$sights->last()['longitude']
        .'&waypoints=';

        foreach($sights as $sight) {
            $distanceUrl .= $sight['latitude'].','.$sight['longitude'].'|';
        } 

        $route = Route::find($route_id);
        if ($route != null) {
            switch ($route['transport']) {
                case 1:
                    $distanceUrl .='&mode=walking';
                    break;
                case 3:
                    $distanceUrl .='&mode=transit';
                    break;
            }
        }
        
        $distanceUrl .= '&key='.env('GOOGLE_MAPS_API_KEY');
        $jsonfile = file_get_contents($distanceUrl);
        $jsonarray = json_decode($jsonfile);

        $distance = 0;
        if (isset($jsonarray->routes[0]) && isset($jsonarray->routes[0]->legs)) {
            foreach($jsonarray->routes[0]->legs as $elem) {
                $distance += $elem->distance->value;
            }
        }
        return $distance;
    }

    function deleteSight(int $route_id, int $order) {
        $sight = Sight::getByRouteAndOrder($route_id, $order);

        if ($sight != null) {
            $sight->delete();

            Sight::updateOrder($route_id, $order);
        }
    }

    public function addSight(Request $request)
    {
        $route_id = $request->input('route_id');
        $order = $request->input('order');
        $length = $request->input('length');

        switch ($request->input('action')) {
            case 'prev':
                return $this->showSight($route_id, $order-1, $length);
            case 'next':
                return $this->showSight($route_id, $order+1, $length);
            case 'delete':
                $this->deleteSight($route_id, $order);
                if ($order == 1) {
                    $order += 1;
                }
                return $this->showSight($route_id, $order-1, $length-1);
        }

        $request->validate([
            'name' => 'required|max:255',
            'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'audio' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $sight_data = $request->only('name', 'description', 'latitude', 'longitude');
        $sight_data['priority'] = $order;
        $sight_data['route_id'] = $route_id;

        $image_paths = [];
        if ($request->has('uploaded_images')) {
            foreach ($request->uploaded_images as $uploaded_image_url) {
                array_push($image_paths, $uploaded_image_url);
            }
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->storePublicly('sights/' . $route_id . '/images', 'public');
                array_push($image_paths, 'storage/' . $path);
            }
        }
        $sight_data['images'] = json_encode($image_paths);

        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->storePublicly('sights/' . $route_id . '/audio', 'public');
            $sight_data['audio'] = json_encode('storage/' . $path);
        }

        // TODO: CHECK FOR VIDEO

        Sight::updateOrCreate($sight_data);
    
        switch($request->input('action')) {
            case 'save':
                return $this->showSight($route_id, $order, $length);
            case 'new':
                return $this->showSight($route_id, $length+1, $length+1);
            case 'end':
                $this->addCityToRoute($route_id);
                Route::addLength($route_id, $this->getDistance($route_id));
                Route::addOrigin($route_id);
                break;
        }
    
        return $this->showrouteInfo($route_id);
    }

    public function showrouteInfo(int $route_id)
    {
        $route = Route::find($route_id);
        $name = $route['name'];
        $description = $route['description'];
        $option = $route['transport'];
        $length = Sight::getSightsAmount($route_id);

        return view("route/overview", compact('length', 'name', 'description', 'option'));
    }

    public function repopulateSights(Request $request) {
        $route_id = $request->session()->get('route_id');
        $order = $request->old('order');
        if (!$order) {
            $order = 1;
        }

        $length = $request->old('length');
        if (!$length) {
            $length = 1;
        }
    
        $longitude = $request->old('longitude');
        $latitude = $request->old('latitude');
        $name = $request->old('name');
        $description = $request->old('description');

        $key = env('GOOGLE_MAPS_API_KEY');

        return view("route/sight", compact('key', 'route_id', 'order', 'length', 'longitude', 'latitude', 'name', 'description'));
    }

    public function repopulateRoute(Request $request) {
        $name = $request->old('name');
        $description = $request->old('description');
        $option = $request->old('transport');

        return view("route/route", compact('name', 'description', 'option'));
    }


}
