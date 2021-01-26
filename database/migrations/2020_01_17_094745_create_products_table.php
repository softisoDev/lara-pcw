<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('codes');
            $table->bigInteger('brand_id')->nullable();
            $table->mediumText('title');
            $table->string('manufacturer', 150)->nullable();
            $table->longText('description')->nullable();
            $table->longText('features')->nullable();
            $table->string('weight', 50)->nullable();
            $table->string('dimensions', 350)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::unprepared('ALTER TABLE products AUTO_INCREMENT=10000001;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
