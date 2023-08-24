<?php

use App\Enums\MerchItemType;
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
         * @see \App\Models\MerchSale
         */
        Schema::create('merch_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount');
            $table->float('price');
            $table->string('currency', 3)->default('USD');
            $table->enum(
                'item_type',
                array_column(MerchItemType::cases(), 'value')
            );
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merch_sales');
    }
};
