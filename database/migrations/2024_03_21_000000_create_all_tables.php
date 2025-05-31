<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'contestant'])->default('contestant');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Cache table for Laravel's cache system
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Cache locks table
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Jobs table for queues
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Failed jobs table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Problems table
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('input_format');
            $table->text('output_format');
            $table->text('constraints');
            $table->text('sample_input');
            $table->text('sample_output');
            $table->text('explanation')->nullable();
            $table->string('difficulty')->default('medium');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Submissions table
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->text('code');
            $table->string('language_id');
            $table->string('status')->default('pending');
            $table->string('verdict')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('execution_time')->nullable();
            $table->integer('memory_usage')->nullable();
            $table->timestamps();
        });

        // Comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // Contests table
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('password');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Contest Problems pivot table
        Schema::create('contest_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Contest Participants pivot table
        Schema::create('contest_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at');
            $table->timestamps();
        });

        // Add contest_id to submissions table
        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('contest_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['contest_id']);
            $table->dropColumn('contest_id');
        });
        Schema::dropIfExists('contest_participants');
        Schema::dropIfExists('contest_problems');
        Schema::dropIfExists('contests');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('problems');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('users');
    }
}; 