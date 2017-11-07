<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("game_sessions", function(Blueprint $table){
            $table->increments("id");
            $table->string("game_id");
            $table->string("starter_id"); //id line starter
            $table->string("group_id");
            $table->tinyInteger("status"); //0 waiting, 1 active, 2 canceled
            $table->softDeletes();
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
        Schema::dropIfExists("game_sessions");
    }
}
