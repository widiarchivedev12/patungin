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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_member_id')->constrained('group_members')->restrictOnDelete();
            $table->foreignId('to_member_id')->constrained('group_members')->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('proof_id')->nullable()->constrained('attachments')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
