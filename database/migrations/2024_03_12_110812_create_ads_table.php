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
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->integer('price')->nullable();
            $table->string('images')->nullable();
            $table->string('status')->nullable();
            $table->boolean('visibility')->nullable();
            $table->enum('price_type', ['Fixe', 'Débattable'])->nullable();
            $table->enum('delivery_status', ['Oui', 'Non'])->nullable();
            $table->enum('state', ['Neuf', 'Usé'])->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('user_subscription_id')
            ->contrained('user_subscriptions');
            // $table->foreignId('user_subscription_id')->nullable();
            // $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');
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
