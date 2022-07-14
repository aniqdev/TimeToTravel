<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sight extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'name',
        'description',
        'latitude',
        'longitude',
        'priority',
    ];

    protected $hidden = [
        'route_id',
        'created_at',
        'updated_at'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function video()
    {
        return $this->hasMany(SightVideo::class);
    }

    public function audio()
    {
        return $this->hasMany(SightAudio::class);
    }

    public function images()
    {
        return $this->hasMany(SightImage::class)->orderBy('order');
    }

    public static function getByRouteAndOrder($route_id, $order) {
        return Sight::where([
            ['route_id', '=', $route_id],
            ['priority', '=', $order],
        ])->first();
    }

    public static function updateOrder($route_id, $order) {
        $sights = Sight::where([
            ['route_id', '=', $route_id],
            ['priority', '>', $order],
        ])->get();

        foreach ($sights as $elem) {
            $elem['priority'] -= 1;
            $elem->save();
        }
        return;
    }

    public static function getAllByRoute($route_id) {
        return Sight::where([
            ['route_id', '=', $route_id],
        ])->orderBy('priority', 'ASC')->get();
    }

    public static function updateOrCreate($sight_data) {
        $sight = Sight::getByRouteAndOrder($sight_data['route_id'], $sight_data['priority']);
        if ($sight == null) {
            $sight = new Sight();
        }

        $sight['name'] = $sight_data['name'];
        $sight['description'] = $sight_data['description'];
        $sight['latitude'] = $sight_data['latitude'];
        $sight['longitude'] = $sight_data['longitude'];
        $sight['priority'] = $sight_data['priority'];
        $sight['route_id'] = $sight_data['route_id'];
        $sight['images'] = $sight_data['images'];

        if (isset($sight_data['audio'])) {
            $sight['audio'] = $sight_data['audio'];
        }
    
        if (isset($sight_data['video'])) {
            $sight['video'] = $sight_data['video'];
        }

        $sight->save();
        return;
    }

    public static function getSightsAmount($route_id) {
        return Sight::where([['route_id', '=', $route_id]])->count();
    }
}
