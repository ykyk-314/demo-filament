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
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('名前');
            $table->string('name_kana', 100)->comment('名前（カナ）');
            $table->tinyInteger('status')->comment('審査ステータス');
            $table->string('email', 100)->comment('メールアドレス');
            $table->string('introducer', 100)->nullable()->comment('サイト紹介者名');
            $table->string('interpreter_number', 50)->comment('通訳案内士番号');
            $table->date('interpreter_number_at')->comment('通訳案内士番号取得年月日');
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
        Schema::dropIfExists('applies');
    }
};
