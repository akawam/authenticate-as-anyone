<?php

return [
    'route-prefix' => 'authenticate-as-anyone', //required
    'middlewares' =>
        [
            'auth', //optional
        ],
    'models' =>
        [
            //required name of the Model
            'User' =>
                [
                    'namespace' => 'App\Models',//optional (default is App\Models)
                    'columns' => [
                        'name' => 'name', //optional (default is name)
                        'firstname' => 'firstname', //optional (default is firstname)
                        'login' => 'email',//optional (default is email)
                    ],
                ],
        ],
];
