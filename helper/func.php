<?php

if (!function_exists('createUUIDAttribute')) {

    /**
     * Create uuid attribute
     *
     * @return array<string, string>
     */
    function createUUIDAttribute() {
        return [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
        ];
    }
}

if (!function_exists('is_testing')) {

    /**
     * Check environment mode
     *
     * @return boolean
     */
    function is_testing() {
        return config('app.env') == 'testing';
    }
}


if (!function_exists('createUUIdField')) {

    /**
     * Create UUID database field
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * 
     * @return void
     */
    function createUUIdField(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string('uuid', 60);
    }
}

if (!function_exists('createBigInteger')) {

    /**
     * Create big integer field
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param string $fieldName
     * 
     * @return void
     */
    function createBigInteger(\Illuminate\Database\Schema\Blueprint $table, string $fieldName) {
        $table->bigInteger($fieldName)->unsigned();
    }
}

if (!function_exists('createNullable')) {

    /**
     * Create nullable field
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param string $fieldName
     * @param integer $length
     * 
     * @return void
     */
    function createNullable(\Illuminate\Database\Schema\Blueprint $table, string $fieldName, int $length = 100) {
        $table->string($fieldName, $length)->default(NULL)->nullable();
    }
}

if (!function_exists('createForeignKey')) {

    /**
     * Create foreign key
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @param string $fieldName
     * @param string $references
     * @param string $on
     * 
     * @return void
     */
    function createForeignKey(\Illuminate\Database\Schema\Blueprint $table, string $fieldName, string $references, string $on) {
        $table->foreign($fieldName)
              ->references($references)
              ->on($on)
              ->onDelete('cascade')
              ->onUpdate('cascade');
    }
}