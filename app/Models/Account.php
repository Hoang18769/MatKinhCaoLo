<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class Account extends Model
{
    use HasApiTokens, HasFactory;
    protected $fillable = [
        'name_account',
        'email_account',
        'password_account',
        'reset_password_token',
        'status_account'
    ];
    protected $attributes = [
        'reset_password_token' => null, // Đặt giá trị mặc định của trường reset_password_token thành null
    ];
    protected $primaryKey = 'id_account';
    protected $hidden = [
        'password_account',
        'remember_token',
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_account');
    }
}
