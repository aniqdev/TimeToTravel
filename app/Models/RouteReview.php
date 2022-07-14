<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'route_id',
        'user_id',
        'mark',
        'approved',
        'title',
        'text',
    ];

    protected $hidden = [
        'route_id',
        'user_id',
        'created_at',
        'updated_at',
        'approved',
    ];

    protected $appends = ['date'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d-m-Y H:i');
    }
}
