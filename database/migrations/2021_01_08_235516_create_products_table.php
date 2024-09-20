<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->double('price', 15, 2);
            $table->double('discount_price', 15, 2)->default(0);
            $table->string('capacity')->default("1")->nullable();
            $table->string('unit')->default("kg")->nullable();
            $table->string('package_count')->nullable();
            $table->integer('available_qty')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('deliverable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('plus_option')->default(true);
            $table->boolean('digital')->default(false);
            $table->boolean('age_restricted')->default(false);
            $table->foreignId('vendor_id')->constrained();
            $table->integer('in_order')->default(1);
            $table->boolean('approved')->default(0);
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
        Schema::dropIfExists('products');
    }
}
