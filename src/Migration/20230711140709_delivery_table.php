<?php
declare(strict_types=1);

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

final class DeliveryTable extends Migration
{
    public function up()
    {
        $this->schema->create('delivery_table',function (Blueprint $table){
            $table->increments('id');
            $table->integer('provider_id');
            $table->integer('week_day');
            $table->integer('delivery_flag');
            $table->time('shipment_time_until')->nullable();
            $table->integer('delivery_day_offset')->nullable();
            $table->integer('vip_flag')->nullable();
            $table->time('vip_time_until')->nullable();
        });
    }

    public function down()
    {
        $this->schema->drop('delivery_table');
    }
}
