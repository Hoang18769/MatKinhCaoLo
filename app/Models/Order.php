<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['id_customer', 'id_payment','name_order','code_order','date_order','email_order','phone_order','address_order',
    'total_order','note','id_discount','status_order','province_order','district_order','commune_order','weight_order','shippingfee'];
    protected $primaryKey = 'id_order';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'id_discount');
    }

    public function paymentmethod()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_order');
    }
    public function feedbackOrder()
    {
        return $this->hasMany(Feedback::class,'id_order');
    }
}
