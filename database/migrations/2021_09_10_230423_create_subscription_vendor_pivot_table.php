<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionVendorPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->string('code')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending','failed', 'cancelled', 'successful','review'])->default('pending');
            $table->dateTime('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_vendor');
    }
}
