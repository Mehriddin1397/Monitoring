<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->after('user_id')->constrained('messages')->nullOnDelete();
            $table->boolean('is_deleted')->default(false)->after('file_mime')->index();
            $table->foreignId('deleted_by')->nullable()->after('is_deleted')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['reply_to_id', 'is_deleted', 'deleted_by']);
        });
    }
};
