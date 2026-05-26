<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->string('location')->nullable()->after('headline');
            $table->text('about')->nullable()->after('location');
            $table->json('skills')->nullable()->after('about');
            $table->json('education')->nullable()->after('skills');
            $table->json('experiences')->nullable()->after('education');
        });
    }

    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'about',
                'skills',
                'education',
                'experiences',
            ]);
        });
    }
};
