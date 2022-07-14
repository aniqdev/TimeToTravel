<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    public $timestamps = false;

    protected $fillable = [
        'city',
        'country',
        'location',
        'latitude',
        'longitude',
        'active',
    ];

    // protected $hidden = [
    //     'latitude',
    //     'longitude',
    // ];

    public function getLocationAttribute()
    {
        return [
            'lat' => $this->attributes['latitude'],
            'lng' => $this->attributes['longitude'],
        ];
    }

}
