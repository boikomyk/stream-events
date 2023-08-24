<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const USER_SOCIAL_COLUMNS = [
        'facebook_id',
        'google_id'
    ];


    public function up(): void
    {
        // add social ids column to the users table
        Schema::table('users', function ($table) {
            foreach (self::USER_SOCIAL_COLUMNS as $social_column_name) {
                $table->string($social_column_name)->nullable();
            }
        });
    }


    public function down(): void
    {
        // remove social ids column from the users table
        Schema::table('users', function ($table) {
            foreach (self::USER_SOCIAL_COLUMNS as $social_column_name) {
                $table->dropColumn($social_column_name);
            }
        });
    }
};
