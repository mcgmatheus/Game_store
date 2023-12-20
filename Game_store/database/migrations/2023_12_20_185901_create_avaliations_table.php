<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('avaliations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->text('avaliation')->limit(1000);
            $table->boolean('recommended');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliations');
    }
};
