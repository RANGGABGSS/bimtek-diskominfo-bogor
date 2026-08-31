<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('speaker_profiles')) {
            Schema::table('speaker_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('speaker_profiles', 'salinan_buku_rekening_path')) {
                    $table->string('salinan_buku_rekening_path')->nullable()->after('foto_npwp_path');
                }
                if (!Schema::hasColumn('speaker_profiles', 'bahan_materi_path')) {
                    $table->string('bahan_materi_path')->nullable()->after('salinan_buku_rekening_path');
                }
            });
        }

        if (Schema::hasTable('event_speakers')) {
            Schema::table('event_speakers', function (Blueprint $table) {
                if (!Schema::hasColumn('event_speakers', 'material_path')) {
                    $table->string('material_path')->nullable()->after('topic');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('speaker_profiles')) {
            Schema::table('speaker_profiles', function (Blueprint $table) {
                $table->dropColumn(['salinan_buku_rekening_path', 'bahan_materi_path']);
            });
        }

        if (Schema::hasTable('event_speakers')) {
            Schema::table('event_speakers', function (Blueprint $table) {
                $table->dropColumn(['material_path']);
            });
        }
    }
};
