<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxiCurrencyPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxi_currency_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->double('base_fare', 15, 2)->default(0.00);
            $table->double('distance_fare', 15, 2)->default(0.00);
            $table->double('time_fare', 15, 2)->default(0.00);
            $table->double('min_fare', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('taxi_currency_pricings');
    }
}
