<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Exception;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Constants
     */
    public const CLIENT_USER_TYPE = "user";
    public const ADMIN_USER_TYPE = "admin";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * **************************************************************
     * GET ROLE OF USER
     * **************************************************************
     * */
    public function role()
    {
        return $this->user_type;
    }

    /**
     * **************************************************************
     * GET USER BY EMAIL
     * **************************************************************
     * */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * **************************************************************
     * GET USER BY FIELD
     * **************************************************************
     * */
    public static function getUserByField($field, $value)
    {
        return self::where($field, $value)->first();
    }

    /**
     * **************************************************************
     * USER JSON RESPONSE
     * **************************************************************
     * */
    public function jsonResponse()
    {
        $json['id'] = $this->id;
        $json['username'] = $this->username;
        $json['first_name'] = $this->first_name;
        $json['last_name'] = $this->last_name;
        $json['email'] = $this->email;
        $json['email_verified_at'] = $this->email_verified_at;
        $json['created_at'] = $this->created_at->toDateTimeString();
        $json['updated_at'] = $this->updated_at->toDateTimeString();
        return $json;
    }
}
