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
        Schema::create('job_profile', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('adress')->nullable();
            $table->string('language')->nullable();
            $table->string('about_me')->nullable();
            $table->string('scolar_level')->nullable();
            $table->string('school')->nullable();
            $table->string('year')->nullable();
            $table->string('trainning')->nullable();
            $table->string('logiciel')->nullable();
            $table->unsignedBigInteger('jobcategory_id')->nullable();
            $table->foreign('jobcategory_id')->references('id')->on('job_categories')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');   
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profile');
    }
};
