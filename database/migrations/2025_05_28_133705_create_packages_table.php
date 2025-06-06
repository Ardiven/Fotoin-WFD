<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->string('duration');
            $table->decimal('custom_duration', 4, 1)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_popular')->default(false);
            $table->timestamps();

            $table->index(['status']);
            $table->index(['is_popular']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};