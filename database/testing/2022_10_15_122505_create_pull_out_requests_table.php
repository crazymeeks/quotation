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
        Schema::create('pull_out_requests', function (Blueprint $table) {
            $table->id();
            createUUIdField($table);
            $table->string('type', 60);
            $table->string('por_no', 60);
            $table->string('business_name', 200)->default(NULL)->nullable();
            $table->text('address')->default(NULL)->nullable();
            $table->string('contact_person', 60)->default(NULL)->nullable();
            $table->string('phone', 20)->default(NULL)->nullable();
            $table->string('salesman', 120)->default(NULL)->nullable();
            $table->string('requested_by', 120)->default(NULL)->nullable();
            $table->string('approved_by', 120)->default(NULL)->nullable();
            $table->string('returned_by', 120)->default(NULL)->nullable();
            $table->string('counter_checked_by', 120)->default(NULL)->nullable();

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
        Schema::dropIfExists('pull_out_requests');
    }
};
