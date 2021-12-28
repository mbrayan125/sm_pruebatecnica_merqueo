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
        
        Schema::create('championships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 128);
            $table->integer("championshipYear", false, true);
            $table->integer("championshipMonth", false, true);
        });

        Schema::create('phases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("orderPhase", false, true);
            $table->string("name", 128);
            $table->unsignedBigInteger("championship_id");
            $table->foreign("championship_id")->references("id")->on("championships");
        });

        Schema::create('phase_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 128);
            $table->unsignedBigInteger("phase_id");
            $table->foreign("phase_id")->references("id")->on("phases");
        });

        Schema::create('team_phase_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("points", false, true);
            $table->unsignedBigInteger("phasegroup_id");
            $table->unsignedBigInteger("team_id");
            $table->foreign("phasegroup_id")->references("id")->on("phase_groups");
            $table->foreign("team_id")->references("id")->on("teams");
        });

        Schema::create('match_games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("matchNumber", false, true);
            $table->string("stadium", 512);
            $table->string("wayToWin", 512)->nullable();
            $table->integer("localGoals", false, true);
            $table->integer("localYellowCards", false, true);
            $table->integer("localRedCards", false, true);
            $table->integer("visitorGoals", false, true);
            $table->integer("visitorYellowCards", false, true);
            $table->integer("visitorRedCards", false, true);
            $table->unsignedBigInteger("championship_id");
            $table->unsignedBigInteger("phasegroup_id");
            $table->unsignedBigInteger("local_team_id");
            $table->unsignedBigInteger("visitor_team_id");
            $table->unsignedBigInteger("winner_team_id")->nullable(true);
            $table->foreign("championship_id")->references("id")->on("championships");
            $table->foreign("phasegroup_id")->references("id")->on("phase_groups");
            $table->foreign("local_team_id")->references("id")->on("teams");
            $table->foreign("visitor_team_id")->references("id")->on("teams");
            $table->foreign("winner_team_id")->references("id")->on("teams");
        });

        Schema::create('match_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("type", 512);
            $table->integer("minute", false, true);
            $table->integer("half", false, true);
            $table->unsignedBigInteger("matchgame_id");
            $table->unsignedBigInteger("player_id");
            $table->foreign("matchgame_id")->references("id")->on("match_games");
            $table->foreign("player_id")->references("id")->on("players");
        });

        Schema::create('match_goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("minute", false, true);
            $table->integer("half", false, true);
            $table->unsignedBigInteger("matchgame_id");
            $table->unsignedBigInteger("player_id");
            $table->foreign("matchgame_id")->references("id")->on("match_games");
            $table->foreign("player_id")->references("id")->on("players");
        });

        Schema::create('player_match_line_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("formationType", 512);
            $table->string("playerBand", 512);
            $table->unsignedBigInteger("matchgame_id");
            $table->unsignedBigInteger("player_id");
            $table->foreign("matchgame_id")->references("id")->on("match_games");
            $table->foreign("player_id")->references("id")->on("players");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_match_line_ups');
        Schema::dropIfExists('match_goals');
        Schema::dropIfExists('match_cards');
        Schema::dropIfExists('match_games');

        Schema::dropIfExists('team_phase_groups');
        Schema::dropIfExists('phase_groups');
        Schema::dropIfExists('phases');
        Schema::dropIfExists('championships');

        Schema::dropIfExists('players');
        Schema::dropIfExists('teams');
    }
}
