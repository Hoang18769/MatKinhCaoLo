<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = ['name_customer', 'email_customer', 'phone_customer',
    'address_customer', 'id_account','id_google','id_facebook'];
    protected $primaryKey = 'id_customer';

    public function account()
    {
        return $this->belongsTo(Account::class, 'id_account');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_customer');
    }
    public function favoritedCustomer()
    {
        return $this->hasMany(Favorite::class, 'id_favorite');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class,'id_customer');
    }
    public function feedbackCustomers()
    {
        return $this->hasMany(Feedback::class,'id_customer');
    }
}
