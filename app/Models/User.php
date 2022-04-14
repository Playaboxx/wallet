<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;
use Request;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, DefaultDatetimeFormat;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'nickname',
        'birth',
        'phone',
        'balance',
        'status',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function isLaravelAdmin()
    {
        $uri = Request::getRequestUri();
        $find = config('admin.route.prefix');
        return strpos($uri, $find) === 1;
    }

    public function toArray()
    {
        if ($this->isLaravelAdmin()) {
            User::makeVisible([
                'id',
                'password',
                'remember_token',
                'email_verified_at',
                'created_at',
                'updated_at'
            ]);
        }
        return parent::toArray();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function UserRank()
    {
        return $this->hasOne(UserRank::class);
    }

    public function UserBank()
    {
        return $this->hasMany(UserBank::class);
    }

    public function withdraw()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function deposit()
    {
        return $this->hasMany(Deposit::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function getName($id)
    {
        return User::where('id', $id)->value('name');
    }
}
