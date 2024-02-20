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
            $table->integer('price')->nullable();
            $table->string('mainly_image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->string('tertiary_image')->nullable();
            $table->boolean('visibility')->nullable();
            $table->enum('delivery_status', ['Oui', 'Non'])->nullable(false);
            $table->enum('state', ['Oui', 'Non'])->nullable(false);
            $table->foreignId('user_id')->nullable()
                ->constrained('users');
                // ->onUpdate('cascade')
                // ->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()
                ->constrained('sub_categories');
                // ->onUpdate('cascade')
                // ->onDelete('cascade');
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
