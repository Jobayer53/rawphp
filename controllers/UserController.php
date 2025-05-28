<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../helpers/response.php';

class UserController
{
    private $pdo;
    private $userModel;

    public function __construct()
    {
        $this->pdo = getDBConnection();
        $this->userModel = new User($this->pdo);
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['name'], $data['email'], $data['password']) || empty($data['name']) || empty($data['email']) || empty($data['password']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL ))  {
            return jsonResponse(['error' => 'Invalid input. name, email and password are required'], 400);
        }
        
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) return jsonResponse(['error' => 'Email already exists'], 409);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $success = $this->userModel->create($data['name'], $data['email'], $hashedPassword, $data['role']?? 'agent');
        if (!$success) {
            return jsonResponse(['error' => 'Registration failed'], 500);
        }
        $user = $this->userModel->findByEmail($data['email']);


        $token = bin2hex(random_bytes(32));
        $tokenFile = __DIR__ . '/../storage/tokens.json';
        $tokens = file_exists($tokenFile) ? json_decode(file_get_contents($tokenFile), true) : [];


        $tokens[$token] = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        file_put_contents($tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));

        return jsonResponse([
            'message' => 'User registered successfully',
            'token' => $token
        ], 201);
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['email'], $data['password']) || empty($data['email']) || empty($data['password'])) {
            return jsonResponse(['error' => 'Missing credentials'], 400);
        }

        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            return jsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = bin2hex(random_bytes(32));
        $tokenFile = __DIR__ . '/../storage/tokens.json';

        // Load existing tokens
        $tokens = file_exists($tokenFile) ? json_decode(file_get_contents($tokenFile), true) : [];

        // Remove old token if exists
        foreach ($tokens as $key => $value) {
            if ($value['user_id'] == $user['id']) {
                unset($tokens[$key]); // remove old token
                break;
            }
        }

        // Add new token
        $tokens[$token] = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Save tokens
        file_put_contents($tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));

        return jsonResponse([
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }
}
