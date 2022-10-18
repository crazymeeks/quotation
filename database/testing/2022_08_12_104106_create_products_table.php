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
            createBigInteger($table, 'unit_of_measure_id', true);
            createBigInteger($table, 'company_id', true);
            createUUIdField($table);
            $table->string('name', 200);
            createNullable($table, 'area');
            createNullable($table, 'code');
            createNullable($table, 'size', 120);
            createNullable($table, 'color', 120);
            createNullable($table, 'manufacturer_part_number');
            createNullable($table, 'purchase_description', 300);
            createNullable($table, 'sales_description', 300);
            $table->decimal('cost', 12,2);
            $table->bigInteger('inventory')->unsigned()->default(0);
            $table->decimal('percent_discount', 3,2)->default(0.00);
            $table->string('status', 15)->default('active');
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
