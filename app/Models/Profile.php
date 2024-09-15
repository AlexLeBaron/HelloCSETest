<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

/**
 * @OA\Schema(
 *      schema="Profile",
 *      description="Profile model",
 *      @OA\Property(property="firstname",type="string",description="Firstname of the profile"),
 *      @OA\Property(property="lastname",type="string",description="Lastname of the profile"),
 *      @OA\Property(property="image",type="string",description="Link to the profile picture of the profile"),
 *      @OA\Property(property="status",type="string",description="Status of the profile"),
 * )
 */
class Profile extends Model
{
    use HasFactory;

    protected string $firstname;
    protected string $lastname;
    protected string $image;
    protected string $status;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'image',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function admin(): Admin
    {
        return $this->belongsTo(Admin::class);
    }

    public function toArray()
    {
        $array = parent::toArray();

        if (auth()->check()) {
            $this->makeVisible('status');
        }

        return parent::toArray();
    }
}
