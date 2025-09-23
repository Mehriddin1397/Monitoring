<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('publish_place');
            $table->string('article_pdf')->nullable();
            $table->string('conclusion_pdf')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('articles');
    }
};
