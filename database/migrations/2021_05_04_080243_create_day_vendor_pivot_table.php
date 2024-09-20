<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDayVendorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_id')->constrained();
            $table->foreignId('vendor_id')->constrained();
            $table->time('open')->nullable();
            $table->time('close')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_vendor');
    }
}
