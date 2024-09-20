<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_services', function (Blueprint $table) {
            $table->id();
            $table->integer('hours')->nullable();
            $table->double('price', 8, 2)->default(0);
            $table->foreignId('order_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->text('options')->nullable();
            $table->string('options_ids')->nullable();
            $table->double('options_price', 8, 2)->default(0)->nullable();
            $table->double('service_price', 8, 2)->default(0)->nullable();
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
        Schema::dropIfExists('order_services');
    }
}
