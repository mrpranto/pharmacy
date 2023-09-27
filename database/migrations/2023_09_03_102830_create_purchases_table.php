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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')
                ->constrained('suppliers', 'id');
            $table->date('date');
            $table->string('status')->index();
            $table->string('reference')->unique()->index();
            $table->double('subtotal');
            $table->double('otherCost')->default(0);
            $table->double('discount')->default(0);
            $table->double('total');
            $table->text('note')->nullable();
            $table->mediumText('purchase_details')->nullable();
            $table->foreignId('created_by')
                ->constrained('users', 'id');
            $table->foreignId('updated_by')
                ->constrained('users', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
