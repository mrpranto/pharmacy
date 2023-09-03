<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')
                ->constrained('purchases', 'id');

            $table->foreignId('product_id')
                ->constrained('products', 'id');
            $table->double('unit_price');
            $table->double('sale_price');
            $table->float('quantity');
            $table->boolean('discountAllow');
            $table->float('discount');
            $table->string('discount_type', 5);
            $table->double('subTotal');
            $table->mediumText('product_details')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_products');
    }
};
