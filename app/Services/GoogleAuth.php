<?php

namespace App\Services;

class GoogleAuth {
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct() {
        $config = require dirname(dirname(__DIR__)) . '/config/google.php';
        $this->clientId = $config['client_id'] ?? '';
        $this->clientSecret = $config['client_secret'] ?? '';
        $this->redirectUri = $config['redirect_uri'] ?? '';
    }

    /**
     * Generate Google Authorization Redirect URL.
     */
    public function getAuthUrl(): string {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'select_account'
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Exchange Auth Code for User Profile Details.
     * 
     * @param string $code OAuth authorization code returned by Google
     * @return array|null User details array, or null on failure
     */
    public function getUserInfo(string $code): ?array {
        // 1. Exchange auth code for access token
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $tokenParams = [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        
        $tokenResponse = curl_exec($ch);
        curl_close($ch);

        if (!$tokenResponse) {
            logMessage('ERROR', "Google OAuth token exchange failed: No response.");
            return null;
        }

        $tokenData = json_decode($tokenResponse, true);
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            logMessage('ERROR', "Google OAuth token exchange failed: " . ($tokenData['error_description'] ?? $tokenResponse));
            return null;
        }

        // 2. Fetch User profile information using the access token
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$accessToken}"
        ]);
        
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        if (!$userInfoResponse) {
            logMessage('ERROR', "Google OAuth user info fetch failed.");
            return null;
        }

        $userData = json_decode($userInfoResponse, true);
        
        if (empty($userData['sub'])) {
            logMessage('ERROR', "Google OAuth user data invalid: " . $userInfoResponse);
            return null;
        }

        return [
            'google_id' => $userData['sub'],
            'email' => $userData['email'] ?? '',
            'email_verified' => $userData['email_verified'] ?? false,
            'name' => $userData['name'] ?? '',
            'picture' => $userData['picture'] ?? '',
            'gender' => $userData['gender'] ?? null
        ];
    }
}
