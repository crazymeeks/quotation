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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            createBigInteger($table, 'role_id');
            createUUIdField($table);
            $table->string('firstname', 60);
            $table->string('lastname', 60);
            $table->string('username', 30);
            $table->string('password', 60);
            $table->enum('require_password_change', ['0', '1'])->default('0')->comment('0-not required 1-required');
            $table->timestamp('deactivated_at')->default(NULL)->nullable();
            $table->softDeletes();
            $table->timestamps();

            createForeignKey($table, 'role_id', 'id', 'roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
