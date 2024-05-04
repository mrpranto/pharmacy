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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers', 'id');
            $table->string('sku', 30)->index()->unique();
            $table->double('mrp')->default(0);
            $table->double('unit_price')->default(0);
            $table->double('unit_percentage')->default(0);
            $table->double('sale_price')->default(0);
            $table->double('sale_percentage')->default(0);
            $table->integer('purchase_quantity')->default(0);
            $table->integer('opening_stock_quantity')->default(0);
            $table->integer('sale_quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
