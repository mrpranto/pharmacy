<?php

use App\Models\Sale\Sale;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 100)->unique();
            $table->timestamp('invoice_date');
            $table->foreignId('customer_id')->constrained('customers', 'id');
            $table->float('total_unit_qty', 18, 2)->default(0);
            $table->float('subtotal', 18, 2)->default(0);
            $table->float('other_cost', 18, 2)->default(0);
            $table->float('discount')->default(0);
            $table->float('grand_total', 18, 2)->default(0);
            $table->string('status', 50)->default(Sale::STATUS_CONFIRMED);
            $table->mediumText('invoice_details')->nullable();
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
        Schema::dropIfExists('sales');
    }
};
