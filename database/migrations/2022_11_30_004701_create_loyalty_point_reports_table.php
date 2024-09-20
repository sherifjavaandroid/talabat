<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPointReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_point_reports', function (Blueprint $table) {
            $table->id();
            $table->double('points', 15,2)->default(0.00);
            $table->double('amount', 15,2)->default(0.00);
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_point_id')->constrained()->onDelete('cascade');
            $table->boolean('is_credit')->default(false);
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
        Schema::dropIfExists('loyalty_point_reports');
    }
}
