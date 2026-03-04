<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('participant_type', ['mahasiswa', 'panitia']);
            $table->unsignedBigInteger('participant_id');
            $table->dateTime('scan_time');
            $table->string('status')->default('hadir');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
