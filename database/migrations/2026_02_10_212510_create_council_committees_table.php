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
        Schema::create('councils_committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('councilId')->constrained(
                table:'councils', indexName:'council_id'
            );
            $table->foreignId('committeeId')->constrained(
                table:'committees', indexName:'committee_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('council_committees');
    }
};
