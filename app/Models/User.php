<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'profile',
        'role_id',
        'firm_name',
        'address',
        'gst_no',
        'mobile',
        'state_id',
        'district',
        'area_id',
        'status',
        'password',
        'bank_name',
        'name_on_passbook',
        'ifsc',
        'account_no',
        'pan_no',
        'created_by',
        'updated_by',
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


    public function role(){
        return $this->belongsTo('App\Models\Role','role_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }

    public function district_data(){
        return $this->belongsTo(District::class,'district');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id');
    }
}
