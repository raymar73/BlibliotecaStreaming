<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Este archivo define las rutas y los controladores correspondientes

return [
    // Rutas para plataformas
    'platforms/list' => ['controller' => 'platformController', 'method' => 'list'],
    'platforms/create' => ['controller' => 'platformController', 'method' => 'create'],
    'platforms/edit' => ['controller' => 'platformController', 'method' => 'edit'],
    'platforms/delete' => ['controller' => 'platformController', 'method' => 'delete'],

    // Rutas para idiomas
    'languages/list' => ['controller' => 'LanguageController', 'method' => 'list'],
    'languages/create' => ['controller' => 'LanguageController', 'method' => 'create'],
    'languages/edit' => ['controller' => 'LanguageController', 'method' => 'edit'],
    'languages/delete' => ['controller' => 'LanguageController', 'method' => 'delete'],

    // Rutas para directores
    'directors/list' => ['controller' => 'DirectorController', 'method' => 'list'],
    'directors/create' => ['controller' => 'DirectorController', 'method' => 'create'],
    'directors/edit' => ['controller' => 'DirectorController', 'method' => 'edit'],
    'directors/delete' => ['controller' => 'DirectorController', 'method' => 'delete'],

    // Rutas para actores
    'actors/list' => ['controller' => 'ActorController', 'method' => 'list'],
    'actors/create' => ['controller' => 'ActorController', 'method' => 'create'],
    'actors/edit' => ['controller' => 'ActorController', 'method' => 'edit'],
    'actors/delete' => ['controller' => 'ActorController', 'method' => 'delete'],

    // Rutas para series
    'series/list' => ['controller' => 'SeriesController', 'method' => 'list'],
    'series/create' => ['controller' => 'SeriesController', 'method' => 'create'],
    'series/edit' => ['controller' => 'SeriesController', 'method' => 'edit'],
    'series/delete' => ['controller' => 'SeriesController', 'method' => 'delete']
];
