<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earning_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('earning_id');
            $table->foreign('earning_id')->references('id')->on('earnings')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained();
            $table->double('earning', 15, 2);
            $table->double('commission', 15, 2)->default(0.00);
            $table->double('balance', 15, 2);
            $table->string('rate')->nullable();
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
        Schema::dropIfExists('earning_reports');
    }
}
