<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->string('table_name')->unique();
            $table->timestamp('last_synced_at')->nullable();
            $table->unsignedBigInteger('last_id')->nullable();
            $table->string('status')->default('idle');
            $table->text('error')->nullable();
            $table->unsignedInteger('rows_synced')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_checkpoints');
    }
};
