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

        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->float('unit_price')->default(0);
            $table->float('mrp')->default(0);
            $table->float('original_sale_price')->default(0);
            $table->float('sale_price', 14, 2)->default(0);
            $table->float('sale_percentage')->default(0);
            $table->integer('quantity');
            $table->float('subtotal', 18, 2)->default(0);
            $table->float('profit_amount')->default(0);
            $table->float('total_profit_amount')->default(0);
            $table->text('sale_product_details')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_products');
    }
};
