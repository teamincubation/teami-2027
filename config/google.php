<?php

return [
    'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
    'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
    'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URL'] ?? 'https://teamincubation.in/auth/google/callback',
    'scopes' => [
        'email',
        'profile'
    ],
];
