<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applications', 'cover_letter')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->text('cover_letter')->nullable()->after('job_post_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'cover_letter')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('cover_letter');
            });
        }
    }
};
