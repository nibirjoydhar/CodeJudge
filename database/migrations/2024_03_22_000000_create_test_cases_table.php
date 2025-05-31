<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->text('input')->nullable();
            $table->text('expected_output');
            $table->boolean('is_sample')->default(false);
            $table->integer('points')->default(10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_cases');
    }
}; 