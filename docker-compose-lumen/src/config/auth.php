<?php
return [
'defaults' => [
    'guard' => env('AUTH_GUARD', 'api'),
    'passwords' => 'users',
],

'guards' => [
    'api' => [
      'driver' => 'jwt',
      'provider' => 'users'
    ],
  ],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  =>  App\Models\User::class,
    ]
],
'bearerAuth' => [ // Unique name of security
  'type' => 'apiKey', // Valid values are "basic", "apiKey" or "oauth2".
  'description' => 'Enter token in format (Bearer <token>)',
  'name' => 'Authorization', // The name of the header or query parameter to be used.
  'in' => 'header', // The location of the API key. Valid values are "query" or "header".
],
];