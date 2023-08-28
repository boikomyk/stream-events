<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAMES_TO_UPDATE = [
        'followers',
        'subscribers',
        'donations',
        'merch_sales'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * @see \App\Models\Traits\ReadableTrait
         */
        foreach (self::TABLE_NAMES_TO_UPDATE as $table_name) {
            Schema::table($table_name, function ($table) {
                $table->boolean('is_read')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLE_NAMES_TO_UPDATE as $table_name) {
            Schema::table($table_name, function ($table) {
                $table->dropColumn('is_read');
            });
        }
    }
};
