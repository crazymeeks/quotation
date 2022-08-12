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
            $table->string('area', 100);
            $table->string('product_name', 200);
            createNullable($table, 'image', 300);
            createNullable($table, 'short_description', 300);
            $table->longText('description')->default(NULL)->nullable();
            $table->decimal('price', 12, 2);
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
