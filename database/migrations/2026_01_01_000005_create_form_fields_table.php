<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimtek_event_id')->constrained('bimtek_events')->onDelete('cascade');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'number', 'select', 'radio', 'checkbox', 'file', 'date'])->default('text');
            $table->json('field_options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
