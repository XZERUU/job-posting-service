<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->string('linkedin_url')->nullable()->after('resume_path');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('github_url')->nullable()->after('portfolio_url');
        });
    }

    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->dropColumn(['linkedin_url', 'portfolio_url', 'github_url']);
        });
    }
};
