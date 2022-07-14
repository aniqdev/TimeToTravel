<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'description',
        'avatar',
        'rating',
        'reviews_count',
        'routes_count',
        'socials',
    ];

    protected $guarded = [
        'password',
        'role', // 0: undefined; 1: author; 2: admin;
        'status' // 0: undefined; 1: pending; 2: accepted; 3: declined;
    ];

    protected $hidden = [
        'password',
        'role', // 0: undefined; 1: author; 2: admin;
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'status',
    ];

    // protected $appends = ['socials'];

    public function getAvatarAttribute()
    {
        return asset($this->attributes['avatar']);
    }

    // public function getRoutesCountAttribute()
    // {
    //     return $this->routes->count();
    // }

    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     $array['paid'] = $this->paid;
    //     return $array;
    // }

    public function get_status()
    {
        return @[
          'undefined', 'pending', 'accepted', 'declined',
        ][$this->status];
    }

    // public static function create($user_data) {
    //     $user = new User;

    //     $user['name'] = $user_data['name'];
    //     $user['surname'] = $user_data['surname'];
    //     $user['email'] = $user_data['email'];
    //     $user['password'] = Hash::make($user_data['password']);

    //     if (!isset($user_data['description'])) {
    //         $user_data['description'] = '';
    //     }
    
    //     if (!isset($user_data['avatar'])) {
    //         $user_data['avatar'] = '';
    //     }
    
    //     $user['avatar'] = $user_data['avatar'];
    //     $user['description'] = $user_data['description'];

    //     $user->save();
    //     return;
    // }

    public static function updateById($user_data) {
        $user = User::find($user_data['id']);

        $user['name'] = $user_data['name'];
        $user['surname'] = $user_data['surname'];
        $user['email'] = $user_data['email'];
        $user['description'] = $user_data['description'];

        if (isset($user_data['password'])) {
            $user['password'] = Hash::make($user_data['password']);
        }
    
        if (isset($user_data['avatar'])) {
            $user['avatar'] = $user_data['avatar'];
        }

        $user['socials'] = $user_data['socials'];

        return $user->save();
    }

    public static function updateAvatar(int $id, string $avatar) {
        $user = User::find($id);
        $user['avatar'] = $avatar;
        $user->save();
        return;
    }

    public static function getByEmail($email) {
        return User::where('email', $email)->first();
    }

    public function getSocialsAttribute()
    {
        $socials = json_decode($this->attributes['socials'], true) ?? [];

        $socials = array_merge([
            'phone' => '',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ], $socials);

        return json_decode(json_encode($socials));
    }
}
