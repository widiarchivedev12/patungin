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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paid_by_member_id')->constrained('group_members')->restrictOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('description');
            $table->string('category')->nullable();
            $table->enum('split_type', ['equal', 'percentage', 'exact', 'by_item'])->default('equal');
            $table->date('spent_at');
            $table->enum('source', ['manual', 'ai_text', 'ai_receipt'])->default('manual');
            $table->foreignId('attachment_id')->nullable()->constrained('attachments')->nullOnDelete();
            $table->boolean('is_draft')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
