<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lyrics', function (Blueprint $table) {
            $table->text('about')->nullable()->after('image_path');
            $table->string('thumbnail_path')->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lyrics', function (Blueprint $table) {
            $table->dropColumn('about');
            $table->dropColumn('thumbnail_path');
        });
    }
};
