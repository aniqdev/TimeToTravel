<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sight_id',
        'title',
        'url',
    ];

    protected $hidden = [
        'sight_id',
        'created_at',
        'updated_at'
    ];

    public function sight()
    {
        return $this->belongsTo(Sight::class);
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
