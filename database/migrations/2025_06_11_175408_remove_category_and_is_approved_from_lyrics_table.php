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
            $table->dropColumn('category');
            $table->dropColumn('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lyrics', function (Blueprint $table) {
            $table->string('category')->after('artist');
            $table->boolean('is_approved')->default(false)->after('user_id');
        });
    }
};
