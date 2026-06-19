<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Team Incubation',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'local',
    'key' => $_ENV['APP_KEY'] ?? 'fallback-key-32-chars-at-least-!!!',
    'url' => $_ENV['APP_URL'] ?? 'https://teamincubation.in',
    
    // Persistent Storage Path Overrides (Isolated from Git deployment)
    'paths' => [
        'public_media' => $_ENV['PUBLIC_MEDIA_PATH'] ?? '/home/username/persistent/team-incubation/public-media',
        'private_storage' => $_ENV['PRIVATE_STORAGE_PATH'] ?? '/home/username/persistent/team-incubation/private-files',
        'logs' => $_ENV['LOG_PATH'] ?? '/home/username/persistent/team-incubation/logs',
        'cache' => $_ENV['CACHE_PATH'] ?? '/home/username/persistent/team-incubation/cache',
        'temp' => $_ENV['TEMP_PATH'] ?? '/home/username/persistent/team-incubation/temporary',
    ],
    
    // Migrator secret
    'setup_secret' => $_ENV['SETUP_SECRET'] ?? 'SetupRequired2026',
];
