<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorOpenStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_open_statuses', function (Blueprint $table) {
            $table->id();
            //vendor_id
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            //open_status
            $table->boolean('is_open')->default(false);
            //auto_set
            $table->boolean('auto_set')->default(true);
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
        Schema::dropIfExists('vendor_open_statuses');
    }
}