<?php

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
         * @see \App\Models\Donation
         */
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->float('amount')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->text('message');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
