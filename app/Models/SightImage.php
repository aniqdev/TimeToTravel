<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightImage extends Model
{
	use HasFactory;

	protected $fillable = [
		'id',
		'sight_id',
		'title',
		'url',
		'order',
	];

	protected $hidden = [
		'sight_id',
		'created_at',
		'updated_at'
	];

	protected $appends = [
		'preview'
	];

	public function getPreviewAttribute()
	{
		return asset(str_replace('sight-images', 'sight-images-previews', $this->attributes['url']));
	}

	public function getUrlAttribute()
	{
		return asset($this->attributes['url']);
	}

	public function urlAttribute()
	{
		return $this->attributes['url'];
	}

	public function sight()
	{
		return $this->belongsTo(Sight::class);
	}
}
