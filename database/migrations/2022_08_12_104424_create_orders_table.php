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
