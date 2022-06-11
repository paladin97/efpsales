<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','avatar','bg_path','status','last_name', 'email', 'password','profile_url','person_id','last_login_ip','last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'access_companies');
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function adminlte_image()
    {
        return asset('storage/uploads/users/'.$this->avatar);
        // return Storage::url('users/'.$this->avatar);
    }

    public function profile_url()
    {
        return $this->profile_url;
        // return Storage::url('users/'.$this->avatar);
    }

    //Método para actualizar el usuario
    public function update_lastlogin()
    {
        return $this->update(['last_login_at' => Carbon::now()->toDateTimeString()]);
    }
}
