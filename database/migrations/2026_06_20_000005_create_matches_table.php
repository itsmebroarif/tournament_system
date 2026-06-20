<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('round');
            $table->foreignId('team_a_registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->foreignId('team_b_registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->foreignId('winner_registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
