<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('event_registrations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('bimtek_events')->onDelete('cascade');
            $table->enum('role_type', ['peserta', 'pembicara'])->default('peserta');
            $table->string('attendance_type')->default('absensi_masuk');
            $table->enum('checkin_method', ['qr_scan', 'manual_admin', 'self_verify'])->default('qr_scan');
            $table->foreignId('verified_by_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('checked_in_at')->useCurrent();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
