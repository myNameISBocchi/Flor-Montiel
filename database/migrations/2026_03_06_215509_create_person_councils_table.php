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
        Schema::create('peoples_councils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personId')->constrained(
                table:'peoples', indexName:'peoples_councils_id'
            );
            $table->foreignId('councilId')->constrained(
                table:'councils', indexName:'councils_peoples_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_councils');
    }
};
