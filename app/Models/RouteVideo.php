<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteVideo extends Model
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

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function urlAttribute()
    {
        return $this->attributes['url'];
    }

    public function getEmbedAttribute()
    {
        return preg_replace('/watch\?v=/', 'embed/', $this->url);
    }
}
