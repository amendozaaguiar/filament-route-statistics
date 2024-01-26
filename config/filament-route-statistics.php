<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default sort colun and direction
    |--------------------------------------------------------------------------
    |
    | This option controls the default sort column and direction used.
    | 
    */

    'sort' => [
        'column' => 'id',
        'direction' => 'desc',
    ],

    /*
    |--------------------------------------------------------------------------
    |  Default column to use as username
    |--------------------------------------------------------------------------
    |
    |  This option controls the default column to use as username.
    | 
    */

    'username' => [
        'column' => 'email'
    ],

    /*
    |--------------------------------------------------------------------------
    |  Default column to use as team name
    |--------------------------------------------------------------------------
    |
    |  This option controls the default column to use as team name.
    |  Example:
    |  model: App\Models\Team::class, 
    |  column: 'name'
    */

    'team' => [
        'model' => env('FILAMENT_ROUTE_STATISTICS_TEAM_MODEL', null),
        'column' => 'name'
    ],

    /*
    |--------------------------------------------------------------------------
    |   Default slug for the resource and navigation icon
    |--------------------------------------------------------------------------
    | 
    |  This option controls the default slug for the resource and navigation icon.
    |  and the date format to use in the resource.
    |
    */

    'resource' => [
        'slug' => null,
        'navigation_icon' => 'heroicon-o-chart-bar-square',
        'date_format' => 'M j, Y H:i:s',
    ]
];
