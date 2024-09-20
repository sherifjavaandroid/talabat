<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('base_delivery_fee', 15, 2)->nullable();
            $table->double('delivery_fee', 15, 2)->nullable();
            $table->double('delivery_range', 8, 2)->nullable();
            $table->string('tax')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->double('commission', 8, 2)->nullable();
            $table->boolean('pickup')->default(true);
            $table->boolean('delivery')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('charge_per_km')->nullable();
            $table->boolean('is_open')->default(true);
            $table->foreignId('vendor_type_id')->nullable()->constrained();
            // $table->foreignId('delivery_zone_id')->nullable()->constrained();
            $table->boolean('auto_assignment')->default(true);
            $table->boolean('auto_accept')->default(false);
            $table->boolean('allow_schedule_order')->default(false);
            $table->boolean('has_sub_categories')->default(false);
            // creator_id is added in the users migration
            // $table->foreignId('creator_id')->nullable()->constrained('users);
            $table->double('min_order', 15, 2)->nullable();
            $table->double('max_order', 15, 2)->nullable();
            $table->boolean('use_subscription')->default(false);
            $table->boolean('has_drivers')->default(false);
            //
            $table->string('prepare_time')->nullable();
            //prepare_time_unit: enum('minutes', 'hours', 'days', 'weeks', 'months')
            $table->enum('prepare_time_unit', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('minutes');
            $table->string('delivery_time')->nullable();
            $table->enum('delivery_time_unit', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('minutes');
            $table->integer('in_order')->default(1);
            $table->integer('featured')->default(0);
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
        Schema::dropIfExists('vendors');
    }
}
