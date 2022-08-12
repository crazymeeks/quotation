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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            createUUIdField($table);
            $table->string('area', 100);
            $table->string('name', 200);
            createNullable($table, 'image', 300);
            createNullable($table, 'short_description', 300);
            $table->longText('description')->default(NULL)->nullable();
            $table->decimal('price', 12,2);
            $table->bigInteger('percent_discount')->unsigned()->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
