<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                if (! Schema::hasColumn('profiles', 'headline')) {
                    $table->string('headline')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'phone')) {
                    $table->string('phone', 30)->nullable();
                }
                if (! Schema::hasColumn('profiles', 'location')) {
                    $table->string('location')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'about')) {
                    $table->text('about')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'resume_path')) {
                    $table->string('resume_path')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'skills')) {
                    $table->json('skills')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'education')) {
                    $table->json('education')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'experiences')) {
                    $table->json('experiences')->nullable();
                }
                if (! Schema::hasColumn('profiles', 'created_at')) {
                    $table->timestamps();
                }
            });

            return;
        }

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('location')->nullable();
            $table->text('about')->nullable();
            $table->string('resume_path')->nullable();
            $table->json('skills')->nullable();
            $table->json('education')->nullable();
            $table->json('experiences')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
