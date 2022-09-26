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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            createBigInteger($table, 'customer_id');
            createBigInteger($table, 'user_id');
            createUUIdField($table);
            $table->string('code', 30);
            $table->decimal('percent_discount', 3, 2)->default(0.00);
            $table->string('status', 15)->default('pending');
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
        Schema::dropIfExists('quotations');
    }
};
