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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            createBigInteger($table, 'customer_id');
            createBigInteger($table, 'user_id');
            createUUIdField($table);
            $table->string('reference_no', 20);
            $table->decimal('grand_total', 12, 2);
            $table->decimal('percent_discount', 3, 2)->default(0.00);
            $table->enum('type', ['order', 'quotation'])->default('quotation');
            $table->enum('status', ['0', '1', '2'])->default('1')->comment('0-pending 1-delivered 3-returned');
            $table->timestamps();

            createForeignKey($table, 'customer_id', 'id', 'customers');
            createForeignKey($table, 'user_id', 'id', 'users');
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
};
