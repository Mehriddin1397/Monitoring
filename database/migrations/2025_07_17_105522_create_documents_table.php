<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Fayl nomi
            $table->string('file_path'); // Saqlangan fayl manzili
            $table->string('category'); // Kategoriya (masalan: "moliyaviy", "talim", ...)
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // Yuklagan foydalanuvchi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
