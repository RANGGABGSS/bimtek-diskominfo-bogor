<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_code')->unique();
            $table->string('template_name');
            $table->json('header_config')->nullable();
            $table->longText('body_html');
            $table->string('signee_nama')->nullable();
            $table->string('signee_nip')->nullable();
            $table->string('signee_jabatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
