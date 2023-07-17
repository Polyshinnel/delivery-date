<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryTable extends Model
{
    protected $table = 'delivery_table';
    protected $fillable = [
        'id',
        'provider_id',
        'week_day',
        'delivery_flag',
        'shipment_time_until',
        'delivery_day_offset',
        'vip_flag',
        'vip_time_until'
    ];
    public $timestamps = false;
}