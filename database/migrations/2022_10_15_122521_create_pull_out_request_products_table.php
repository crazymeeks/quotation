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
        Schema::create('pull_out_request_products', function (Blueprint $table) {
            $table->id();
            createBigInteger($table, 'pull_out_request_id');
            $table->integer('quantity');
            $table->string('unit', 120);
            $table->string('product_uuid', 60);
            $table->string('product_name', 300);
            $table->string('code', 120)->default(NULL)->nullable();
            $table->text('purchase_description')->default(NULL)->nullable();
            $table->string('size', 120)->default(NULL)->nullable();
            $table->string('color', 120)->default(NULL)->nullable();
            $table->text('remarks')->default(NULL)->nullable();
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
        Schema::dropIfExists('pull_out_request_products');
    }
};
