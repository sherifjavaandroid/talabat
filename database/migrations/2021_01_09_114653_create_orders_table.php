<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('verification_code')->nullable();
            $table->string('note')->nullable();
            $table->string('reason')->nullable();
            $table->enum('payment_status', ['pending', 'request', 'review', 'failed', 'cancelled', 'successful'])->default('pending');
            $table->double('sub_total', 15, 2)->default(0);
            $table->double('tip', 15, 2)->default(0);
            $table->double('discount', 15, 2)->default(0);
            $table->double('delivery_fee', 15, 2)->default(0);
            $table->double('commission', 15, 2)->default(0);
            $table->double('tax', 15, 2)->default(0);
            $table->double('tax_rate', 15, 2)->default(0);
            $table->text('fees')->nullable();
            $table->double('total', 15, 2)->default(0);
            $table->foreignId('delivery_address_id')->nullable()->constrained();
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->foreignId('package_type_id')->nullable()->constrained();
            $table->double('weight', 10, 2)->default(0);
            $table->double('width', 10, 2)->default(0);
            $table->double('length', 10, 2)->default(0);
            $table->double('height', 10, 2)->default(0);
            $table->boolean('payer')->default(true);
            //end package delivery columns
            $table->foreignId('payment_method_id')->nullable()->constrained();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained("users")->onDelete('cascade');
            
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
        Schema::dropIfExists('orders');
    }
}
