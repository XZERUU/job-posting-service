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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->string('job_title');
            $table->longText('job_description');
            $table->string('job_type')->default('full-time');
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('location');
            $table->integer('vacancies')->default(1);
            $table->text('requirements')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('closing_date')->nullable();
            $table->timestamps();

            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
