<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 100)->nullable();
            $table->string('user_name', 100)->default('default user')->nullable();
            $table->mediumText('title')->nullable();
            $table->longText('text');
            $table->integer('num_helpful')->default(0)->nullable();
            $table->smallInteger('rating')->default(0)->nullable();
            $table->text('source_url')->nullable();
            $table->date('published_at')->nullable();
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
        Schema::dropIfExists('reviews');
    }
}
