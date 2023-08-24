<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SubscriptionTier;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * @see \App\Models\Subscriber
         */
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->enum(
                    'subscription_tier',
                    array_column(SubscriptionTier::cases(), 'value')
                )
                ->default(SubscriptionTier::TIER_1->value);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
