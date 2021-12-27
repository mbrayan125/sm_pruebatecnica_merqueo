<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampeonatoData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('championships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 128);
            $table->integer("championshipYear", false, true);
            $table->integer("championshipMonth", false, true);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("country", 128);
            $table->string("flag", 128);
            $table->integer("rank", false, true);
            $table->string("nationality", 128);
        });

        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 128);
            $table->string("dorsalName", 128);
            $table->integer("dorsalNumber", false, true);
            $table->integer("birthYear", false, true);
            $table->integer("birthMonth", false, true);
            $table->string("gamePosition", 128);
            $table->string("photoPath", 128);
            $table->unsignedBigInteger("team_id");
            $table->foreign("team_id")->references("id")->on("teams");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('championships');
    }
}
