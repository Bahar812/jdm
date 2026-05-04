<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->index(['is_active', 'category', 'name'], 'products_active_category_name_index');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->index(['payment_status', 'paid_at'], 'orders_payment_paid_at_index');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_at_index');
            $table->index(['status', 'created_at'], 'orders_status_created_at_index');
        });

        Schema::table('order_items', function (Blueprint $table): void {
            $table->index(['product_id', 'order_id'], 'order_items_product_order_index');
        });

        Schema::table('inventory_movements', function (Blueprint $table): void {
            $table->index(['product_id', 'created_at'], 'inventory_product_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table): void {
            $table->dropIndex('inventory_product_created_index');
        });

        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropIndex('order_items_product_order_index');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('orders_payment_paid_at_index');
            $table->dropIndex('orders_payment_created_at_index');
            $table->dropIndex('orders_status_created_at_index');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropIndex('products_active_category_name_index');
        });
    }
};
