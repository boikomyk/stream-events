<?php

use App\Enums\NotificationRecordType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * @see \App\Models\NotificationMapItem
         */
        Schema::create('notification_map_items', function (Blueprint $table) {
            $table->id();
            $table->integer('record_id');
            $table->timestamp('record_created_at');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum(
                'record_type',
                array_column(NotificationRecordType::cases(), 'value')
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_map_items');
    }
};
