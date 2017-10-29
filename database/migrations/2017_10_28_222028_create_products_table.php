<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("products", function(Blueprint $table){
          $table->increments("id");
          $table->integer("category_id");
          $table->integer("user_id");
          $table->string("product_name");
          $table->integer("price");
          $table->string("photo");
          $table->string("description");
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
        Schema::dropIfExists("products");
    }
}
