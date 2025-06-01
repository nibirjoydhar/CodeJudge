<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->text('status')->change();
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
}; 