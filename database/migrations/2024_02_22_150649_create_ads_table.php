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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->string('description')->nullable(false);
            $table->string('country')->nullable(false);
            $table->string('city')->nullable(false);
            $table->integer('phone')->nullable();
            $table->integer('price')->nullable();
            $table->string('images')->nullable();
            $table->boolean('visibility')->nullable();
            $table->enum('price_type', ['Fixe', 'Débattable'])->nullable();
            $table->enum('delivery_status', ['Oui', 'Non'])->nullable(false);
            $table->enum('state', ['Neuf', 'Usé'])->nullable(false);
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
        Schema::dropIfExists('ads');
    }
};
