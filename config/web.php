<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hjgkuybiv68790u8j8hh0897h(B(YVG9788gf6f/f796fOLJBLB))',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => 'mail.c0060181.ferozo.com',
                'username'   => 'test@c0060181.ferozo.com',
                'password'   => 'VKJ*tz@9hG',
                'port'       => '465',
                //'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
          'enablePrettyUrl' => true,
          'enableStrictParsing' => true,
          'showScriptName' => false,
          'rules' => [
              'location/search'        => 'propiedad/search',
              'propiedad/getInfo'      => 'propiedad/get-info',
              'propiedad/create'       => 'propiedad/create',
              'main/general-data'      => 'gral/get-info',
              'search/config'          => 'propiedad/get-search-config',
              'equipamientos/all'      => 'propiedad/equipamientos',

              'user/create'            => 'user/create',
              'user/login'             => 'user/login',
              'user/logout'            => 'user/logout',
              'user/get-types'         => 'user/get-users',
              'user/all'               => 'user/get-all',
              'user/profile'           => 'user/profile',
              'user/editProfile'       => 'user/edit-profile',

              'propiedad/caracteristicas' => 'propiedad/get-caracteristicas',
              'ambientes/all'             => 'propiedad/get-ambientes',
              'servicios/all'             => 'propiedad/get-servicios',
              'denuncia/nueva'         => 'denuncia/nueva',
              'denuncia/all'           => 'denuncia/all',
              'inmobiliaria/getAll'    => 'inmobiliaria/get-all',

              'zonas/all'              => 'zonas/all',
              'zonas/create'           => 'zonas/create',
              'zonas/denominacion/all' => 'zonas/get-denominaciones',
          ],
      ]

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
