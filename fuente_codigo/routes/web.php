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

Route::get('/simulation/load/teams', 'Simulator\FootballController@loadTeamsFromFile');

Route::get('/simulation/load/players', 'Simulator\FootballController@loadPlayersFromFile');

Route::get('/simulation/view/{id}', 'Simulator\FootballController@viewSimulation');

Route::get('/simulation/list', 'Simulator\FootballController@listSimulations');

Route::get('/simulation/new', 'Simulator\FootballController@newSimulation');

Route::get('/simulation/help', 'Simulator\FootballController@simulationHelp');

Route::get('/simulation/load/help', 'Simulator\FootballController@simulationLoadHelp');