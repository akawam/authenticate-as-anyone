<?php

return [
    'banner-color' => '',
    'route-prefix' => 'authenticate-as-anyone',
    'middlewares' =>
        [
            'auth',
        ],
    'models' =>
        [
            /*
             *  'ModelName' =>
                [
                    'namespace' => 'your namespace' (default 'App\Models'),
                    'columns' => [
                        'name' => 'nom_admin',
                        'firstname' => 'prenom_admin',
                        'login' => 'email',
                    ],
                    'pretty-name' => 'Administrators',
                ],
             *
             * */

            'Admin' =>
                [
                    'namespace' => 'App\Models',
                    'columns' => [
                        'name' => 'nom_admin',
                        'firstname' => 'prenom_admin',
                        'login' => 'email',
                    ],
                    'pretty-name' => 'Administrators',
                ],
            'Owner' =>
                [
                    'namespace' => 'App\Models',
                    'columns' => [
                        'name' => 'nom_utilisateur',
                        'firstname' => 'prenom_utilisateur',
                        'login' => 'email',
                    ],
                    'pretty-name' => 'Owners',
                ],
        ],
];
