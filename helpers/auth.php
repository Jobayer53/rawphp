<?php

function getAuthenticatedUser()
{
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) return null;
    $authHeader = $headers['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) return null;

    $token = $matches[1];
    $tokensFile = __DIR__ . '/../storage/tokens.json';

    if (!file_exists($tokensFile)) return null;

    $tokens = json_decode(file_get_contents($tokensFile), true);
    return $tokens[$token] ?? null;
}
