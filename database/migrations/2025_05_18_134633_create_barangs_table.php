<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->integer('periode')->nullable();
            $table->string('site')->nullable();
            $table->string('description')->nullable();
            $table->string('no')->nullable();
            $table->string('itemid')->nullable();
            $table->string('barcode')->nullable();
            $table->string('nama_item')->nullable();
            $table->string('vendor')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('dept_id')->nullable();
            $table->string('vend_name')->nullable();
            $table->string('ctgry_id')->nullable();
            $table->string('dept_description')->nullable();
            $table->integer('qty')->nullable();
            $table->string('unitid')->nullable();
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->decimal('total_inc_ppn', 15, 4)->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->decimal('gross_amt', 15, 2)->nullable();
            $table->decimal('disc_amt', 15, 2)->nullable();
            $table->decimal('sales_after_discount', 15, 2)->nullable();
            $table->decimal('sales_vat', 15, 2)->nullable();
            $table->decimal('net_sales_bef_tax', 15, 2)->nullable();
            $table->decimal('margin', 15, 2)->nullable();
            $table->decimal('margin_percent', 16, 13)->nullable();
            $table->integer('date')->nullable(); // format numeric Excel
            $table->decimal('time', 12, 10)->nullable();
            $table->string('consignment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
