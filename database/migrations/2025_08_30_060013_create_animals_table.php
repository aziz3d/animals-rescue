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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->string('breed');
            $table->integer('age');
            $table->enum('gender', ['male', 'female']);
            $table->enum('size', ['small', 'medium', 'large']);
            $table->text('description');
            $table->text('medical_history')->nullable();
            $table->enum('adoption_status', ['available', 'pending', 'adopted'])->default('available');
            $table->boolean('featured')->default(false);
            $table->json('images')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('adoption_status');
            $table->index('species');
            $table->index('breed');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
