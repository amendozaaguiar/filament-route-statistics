<?php

return [

    'sort' => [
        'column' => 'id',
        'direction' => 'desc',
    ],
    'username' => [
        'column' => 'email'
    ],
    'team' => [
        'classname' => 'App\Models\Team',
        'column' => 'name'
    ],
    'resource' => [
        'slug' => null,
        'navigation_icon' => 'heroicon-o-chart-bar-square'
    ]
];
