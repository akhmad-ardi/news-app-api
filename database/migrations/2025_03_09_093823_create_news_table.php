<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("title");
            $table->string("slug");
            $table->string("excerpt", 255);
            $table->text("body");
            $table->string('thumbnail');
            $table->timestamps();
        });

        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('news_id');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('pictures');
    }
};
