<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_stops', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_first')->default(false);
            $table->double('price', 8, 2)->default(0);
            $table->foreignId('order_id')->constrained();
            $table->foreignId('stop_id')->nullable()->constrained('delivery_addresses');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('note')->nullable();
            $table->boolean('verified')->default(false);
            //indicate if the order is for package delivery or regular delivery
            // $table->enum('type', ['regular', 'package'])->default('regular');
            // $table->string('recipient_name')->nullable();
            // $table->string('recipient_phone')->nullable();
            // $table->foreignId('pickup_location_id')->nullable()->constrained('delivery_addresses');
            // $table->foreignId('dropoff_location_id')->nullable()->constrained('delivery_addresses');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_stops');
    }
}
