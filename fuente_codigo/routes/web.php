<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create/team', 'Simulator\FootballController@createTeam');

Route::get('/create/player', 'Simulator\FootballController@createPlayer');

Route::get('/load/teams', 'Simulator\FootballController@loadTeamsFromFile');

Route::get('/load/players', 'Simulator\FootballController@loadPlayersFromFile');

Route::get('/start/simulation', 'Simulator\FootballController@startSimulation');

Route::get('/utile/check', 'Simulator\FootballController@utileCheck');
