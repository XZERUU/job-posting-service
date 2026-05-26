<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applications', 'job_post_id')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->foreignId('job_post_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('job_posts')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'job_post_id')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropConstrainedForeignId('job_post_id');
            });
        }
    }
};
