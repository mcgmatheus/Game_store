<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('products_assets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('name');
            $table->string('url');
            $table->enum('types_games_assets', ['MINIMUN', 'RECOMMENDED']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_assets');
    }
};
