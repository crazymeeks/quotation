<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            createBigInteger($table, 'order_id');
            createUUIdField($table);
            $table->string('product_uuid', 60);
            $table->string('company', 200);
            $table->string('product_name', 200);
            $table->string('area', 200)->default(NULL)->nullable();
            $table->string('code', 200)->default(NULL)->nullable();
            $table->string('size', 200)->default(NULL)->nullable();
            $table->string('color', 200)->default(NULL)->nullable();
            $table->string('unit_of_measure', 30);
            createNullable($table, 'manufacturer_part_number', 100);
            createNullable($table, 'purchase_description', 300);
            createNullable($table, 'sales_description', 300);
            $table->decimal('price', 12, 2);
            createBigInteger($table, 'quantity');
            $table->bigInteger('percent_discount')->default(0)->unsigned();
            $table->decimal('final_price', 12, 2);
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
        Schema::dropIfExists('order_products');
    }
};
