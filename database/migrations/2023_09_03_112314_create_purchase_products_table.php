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
            $table->double('mrp')->default(0);
            $table->double('unit_price');
            $table->double('unit_percentage');
            $table->double('sale_price');
            $table->double('sale_percentage');
            $table->float('quantity');
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
