<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @OA\Schema(
 *      schema="Admin",
 *      description="Admin model",
 *      @OA\Property(property="email",type="string",description="Email of the administrator"),
 *      @OA\Property(property="password",type="string",description="Password of the administrator"),
 * )
 */
class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected string $email;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password'
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
        'created_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profiles(): Profile
    {
        return $this->hasMany(Profile::class);
    }
}
