<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxi_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('vehicle_type_id')->constrained();
            $table->foreignId('currency_id')->nullable()->constrained();
            //pickup location
            $table->string('pickup_latitude');
            $table->string('pickup_longitude');
            $table->string('pickup_address');
            //drop off location
            $table->string('dropoff_latitude');
            $table->string('dropoff_longitude');
            $table->string('dropoff_address');
            //breakdown
            $table->string('base_fare')->nullable();
            $table->string('distance_fare')->nullable();
            $table->string('time_fare')->nullable();
            $table->string('trip_distance')->nullable();
            $table->string('trip_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxi_orders');
    }
}
