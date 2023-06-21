<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide_languages', function (Blueprint $table) {
            $table->bigInteger('guide_id')->unsigned()->comment('ガイドID');
            $table->bigInteger('language_id')->unsigned()->comment('言語ID');
            $table->foreign('guide_id')->references('id')->on('applies')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
            $table->unique(['guide_id', 'language_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guide_languages');
    }
};
