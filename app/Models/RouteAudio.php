<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteAudio extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'route_id',
        'title',
        'url',
    ];

    protected $hidden = [
        'route_id',
        'created_at',
        'updated_at'
    ];

    public function getUrlAttribute()
    {
        return asset($this->attributes['url']);
    }

    public function urlAttribute()
    {
        return $this->attributes['url'];
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
