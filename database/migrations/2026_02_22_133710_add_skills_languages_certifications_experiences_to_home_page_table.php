<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page', function (Blueprint $table) {
            $table->json('skills')->nullable()->after('social_links');
            $table->json('languages')->nullable()->after('skills');
            $table->json('certifications')->nullable()->after('languages');
            $table->json('experiences')->nullable()->after('certifications');
        });
    }

    public function down(): void
    {
        Schema::table('home_page', function (Blueprint $table) {
            $table->dropColumn(['skills', 'languages', 'certifications', 'experiences']);
        });
    }
};
