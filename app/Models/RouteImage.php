<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteImage extends Model
{
	use HasFactory;

	protected $fillable = [
		'id',
		'route_id',
		'title',
		'url',
		'order',
	];

	protected $hidden = [
		'route_id',
		'created_at',
		'updated_at'
	];

	protected $appends = [
		'preview'
	];

	public function getUrlAttribute()
	{
		return asset($this->attributes['url']);
	}

	public function getPreviewAttribute()
	{
		return asset(str_replace('route-images', 'route-images-previews', $this->attributes['url']));
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
