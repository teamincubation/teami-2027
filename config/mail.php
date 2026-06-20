<?php

return [
    'host' => $_ENV['MAIL_HOST'] ?? 'smtp.hostinger.com',
    'port' => $_ENV['MAIL_PORT'] ?? 465,
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'ssl', // ssl or tls
    'username' => $_ENV['MAIL_USER'] ?? '',
    'password' => $_ENV['MAIL_PASS'] ?? '',
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'office@teamincubation.in',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Team Incubation',
    ],
    'settings' => [
        'smtp_auth' => true,
        'html' => true,
    ],
];
