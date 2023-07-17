<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class DeliveryExeption extends Migration
{
    public function up()
    {
        $this->schema->create('delivery_exception',function (Blueprint $table){
            $table->increments('id');
            $table->integer('provider_id');
            $table->string('exception_name', 128);
            $table->date('exception_day');
            $table->integer('delivery_flag');
            $table->time('shipment_time_until')->nullable();
            $table->integer('delivery_day_offset')->nullable();
            $table->integer('vip_flag')->nullable();
            $table->time('vip_time_until')->nullable();
        });
    }

    public function down()
    {
        $this->schema->drop('delivery_exception');
    }
}
