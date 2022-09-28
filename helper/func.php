<?php

if (!function_exists('createUUIDAttribute')) {

    /**
     * Create uuid attribute
     *
     * @return array<string, string>
     */
    function createUUIDAttribute() {
        return [
            'uuid' => generateUuid(),
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
     * @param bool $nullable
     * 
     * @return void
     */
    function createBigInteger(\Illuminate\Database\Schema\Blueprint $table, string $fieldName, bool $nullable = false) {
        if ($nullable) {
            $table->bigInteger($fieldName)->default(NULL)->nullable();
            return;
        }
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


if (!function_exists('generateUuid')) {

    /**
     * Generate UUID
     *
     * @return string
     */
    function generateUuid() {
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->__toString();
        return $uuid;
    }
}


if (!function_exists('generate_string')) {

    /**
     * Generate random string
     *
     * @param integer $strength
     * 
     * @return string
     */
    function generate_string($strength = 16, bool $ucase = false) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . uniqid();

        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $ucase ? strtoupper($random_string) : $random_string;
    }
}


if (!function_exists('createRefenceNumber')) {

    /**
     * Create reference number
     *
     * @return string
     */
    function createRefenceNumber(string $prefix = 'REF-') {
        return strtoupper(uniqid($prefix));
    }
}

if (!function_exists('get_discount_price')) {

    /**
     * Get discounted price
     *
     * @param int|float $actual_price
     * @param int|float $discount
     * 
     * @return mixed
     */
    function get_discount_price($actual_price, $discount) {
        $sellingPrice = $actual_price - ($actual_price * ($discount / 100));
        return $sellingPrice;
    }
}