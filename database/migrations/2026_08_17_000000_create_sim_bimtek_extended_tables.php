<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Participant Profiles
        if (!Schema::hasTable('participant_profiles')) {
            Schema::create('participant_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
                $table->string('nik')->nullable();
                $table->string('foto_ktp_path')->nullable();
                $table->string('npwp')->nullable();
                $table->string('foto_npwp_path')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->string('instansi')->nullable();
                $table->string('no_hp')->nullable();
                $table->enum('verification_status', ['belum_diverifikasi', 'terverifikasi', 'perlu_perbaikan'])->default('belum_diverifikasi');
                $table->text('verification_notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Speaker Profiles
        if (!Schema::hasTable('speaker_profiles')) {
            Schema::create('speaker_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
                $table->string('nip_nik')->nullable();
                $table->string('foto_ktp_path')->nullable();
                $table->string('npwp')->nullable();
                $table->string('foto_npwp_path')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->string('instansi')->nullable();
                $table->string('jabatan')->nullable();
                $table->string('golongan')->nullable()->default('Golongan III');
                $table->enum('verification_status', ['belum_diverifikasi', 'terverifikasi', 'perlu_perbaikan'])->default('belum_diverifikasi');
                $table->text('verification_notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Attendance Sessions for Dynamic Time-Based QR Code
        if (!Schema::hasTable('attendance_sessions')) {
            Schema::create('attendance_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('bimtek_events')->onDelete('cascade');
                $table->string('token')->index();
                $table->string('secret_key');
                $table->integer('interval_minutes')->default(10);
                $table->dateTime('valid_from');
                $table->dateTime('valid_until');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Attendances columns are now in base migration (2026_01_01_000008)

        // 4. Tax Parameters for Dynamic PPh 21 Rules
        if (!Schema::hasTable('tax_parameters')) {
            Schema::create('tax_parameters', function (Blueprint $table) {
                $table->id();
                $table->string('category_name');
                $table->boolean('has_npwp')->default(true);
                $table->decimal('tax_rate_percent', 5, 2)->default(5.00);
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. Payment Components (Honorarium & Transport / Uang Jalan)
        if (!Schema::hasTable('payment_components')) {
            Schema::create('payment_components', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('bimtek_events')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('recipient_type', ['peserta', 'pembicara'])->default('peserta');
                $table->enum('component_type', ['honorarium', 'uang_jalan', 'transport'])->default('honorarium');
                $table->decimal('volume', 8, 2)->default(1);
                $table->string('unit', 50)->default('Sesi');
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('gross_amount', 12, 2)->default(0);
                $table->decimal('tax_rate_percent', 5, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('net_amount', 12, 2)->default(0);
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->enum('payment_status', ['pending', 'verified', 'processed', 'paid'])->default('pending');
                $table->dateTime('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 6. Certificates
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('bimtek_events')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('role_type', ['peserta', 'pembicara'])->default('peserta');
                $table->string('certificate_number')->unique();
                $table->string('file_path');
                $table->date('issue_date');
                $table->timestamps();
            });
        }

        // 7. Activity Logs
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('action');
                $table->string('module');
                $table->text('description')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('payment_components');
        Schema::dropIfExists('tax_parameters');
        Schema::dropIfExists('attendance_sessions');
        Schema::dropIfExists('speaker_profiles');
        Schema::dropIfExists('participant_profiles');
    }
};
