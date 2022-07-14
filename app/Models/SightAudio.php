<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightAudio extends Model
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
