<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryException extends Model
{
    protected $table = 'delivery_exception';
    protected $fillable = [
        'id',
        'provider_id',
        'exception_name',
        'exception_day',
        'delivery_flag',
        'shipment_time_until',
        'delivery_day_offset',
        'vip_flag',
        'vip_time_until'
    ];
    public $timestamps = false;
}