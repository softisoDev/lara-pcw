<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->mediumText('title');
            $table->double('current_price', 15, 2)->nullable();
            $table->string('currency', 8)->nullable();
            $table->double('price_max', 15, 2)->nullable();
            $table->double('price_min', 15, 2)->nullable();
            $table->boolean('availability')->default(1)->nullable();
            $table->string('condition', 30)->nullable();
            $table->mediumText('color')->nullable();
            $table->mediumText('size')->nullable();
            $table->text('merchant')->nullable();
            $table->text('source_url');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
