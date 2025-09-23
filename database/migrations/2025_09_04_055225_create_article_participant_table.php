<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('article_participant_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_new_id')->constrained('participants_new')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('article_participant_new');
    }
};
