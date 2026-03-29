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
        Schema::create('peoples_comunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personId')->constrained(
                table:'peoples', indexName:'peoples_comunities_id'
            )->onDelete('cascade');
            $table->foreignId('comunityId')->constrained(
                table:'comunities', indexName:'comunities_peoples_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_comunities');
    }
};
