<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->string('from_payment_status')->nullable();
            $table->string('to_payment_status')->nullable();
            $table->string('source')->default('system')->index();
            $table->text('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['order_id', 'created_at']);
        });

        DB::table('orders')
            ->select(['id', 'status', 'payment_status', 'created_at'])
            ->chunkById(200, function ($orders): void {
                $rows = [];

                foreach ($orders as $order) {
                    $timestamp = $order->created_at ?: now();

                    $rows[] = [
                        'order_id' => $order->id,
                        'from_status' => null,
                        'to_status' => $order->status,
                        'from_payment_status' => null,
                        'to_payment_status' => $order->payment_status,
                        'source' => 'migration',
                        'note' => 'Snapshot status awal saat migrasi histori order.',
                        'changed_by' => null,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }

                if ($rows !== []) {
                    DB::table('order_status_histories')->insert($rows);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
