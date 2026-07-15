<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->string('category');
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('open'); // open, in_progress, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_tickets');
    }
};