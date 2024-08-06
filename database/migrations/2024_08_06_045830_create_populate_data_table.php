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
        Schema::create('populate_data', function (Blueprint $table) {
            $table->id();
            $table->integer('end_year')->nullable();
            $table->decimal('citylng', 10, 8)->nullable();
            $table->decimal('citylat', 10, 8)->nullable();
            $table->integer('intensity')->nullable();
            $table->string('sector')->nullable();
            $table->string('topic')->nullable();
            $table->text('insight')->nullable(); // Use text for larger strings
            $table->string('swot')->nullable();
            $table->string('url')->nullable();
            $table->string('region')->nullable();
            $table->integer('start_year')->nullable();
            $table->string('impact')->nullable();
            $table->timestamp('added')->nullable();
            $table->timestamp('published')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('relevance')->nullable();
            $table->string('pestle')->nullable();
            $table->string('source')->nullable();
            $table->string('title')->nullable();
            $table->integer('likelihood')->nullable();
            $table->timestamps();  // Timestamps are fine as they are
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('populate_data');
    }
};
