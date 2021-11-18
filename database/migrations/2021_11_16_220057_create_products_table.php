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
            $table->id();
            $table->string('pro_name');
            $table->string('pro_price');
            $table->text('pro_details');
            $table->string('pro_stock');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');
                $table->unsignedBigInteger('cat_id')->nullable();
                $table->foreign('cat_id')
                    ->references('id')
                    ->on('pro_categories')
                    ->onDelete('cascade');
                    $table->unsignedBigInteger('brand_id')->nullable();
                    $table->foreign('brand_id')
                        ->references('id')
                        ->on('pro_brands')
                        ->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
