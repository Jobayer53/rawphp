<?php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/ticket.php';


class TicketController
{
    private $ticketModel;
    private $pdo;
    public function __construct()
    {
        $this->pdo = getDBConnection();
        $this->ticketModel = new Ticket($this->pdo);
    }

    public function adminCheck()
    {
        $auth = getAuthenticatedUser();
        if ($auth && $auth['role'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    public function agentCheck()
    {
        $auth = getAuthenticatedUser();
        if ($auth && $auth['role'] == 'agent') {
            return true;
        } else {
            return false;
        }
    }
    public function auth()
    {
        $auth = getAuthenticatedUser();
        if (!$auth) {
           return jsonResponse(['message' => 'Unauthorized! Please login again.'], 401);
        }
        return;
    }

    public function create()
    {
        $this->auth();
        $user = getAuthenticatedUser();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['title'], $data['description'], $data['department_id'])) {
            return jsonResponse(['message' => 'Missing fields'], 400);
        }
      
        $ticketId = $this->ticketModel->create($data['title'], $data['description'], $user['user_id'], $data['department_id']);
        return jsonResponse(['message' => 'Ticket created', 'ticket_id' => $ticketId], 201);
    }
    public function assign($ticketId)
    {
        $this->auth();
        if (!$this->agentCheck()) {
            return jsonResponse(['message' => 'Only agents can assign tickets'], 403);
        }

        $user = getAuthenticatedUser();
        $updated = $this->ticketModel->assignTicket($ticketId, $user['user_id']);

        if ($updated) {
            return jsonResponse(['message' => 'Ticket assigned successfully'], 200);
        } else {
            return jsonResponse(['message' => 'Failed to assign ticket'], 404);
        }
    }
    public function updateStatus($ticketId)
    {
      
          $this->auth();
        

        $data = json_decode(file_get_contents("php://input"), true);
        $validStatuses = ['open', 'in_progress', 'closed'];

        if (!isset($data['status']) || !in_array($data['status'], $validStatuses)) {
            return jsonResponse(['message' => 'Invalid or missing status'], 400);
        }

        $success = $this->ticketModel->updateStatus($ticketId, $data['status']);

        if ($success) {
            return jsonResponse(['message' => 'Status updated'], 200);
        } else {
            return jsonResponse(['message' => 'Ticket not found'], 404);
        }
    }
    public function addNote($ticketId)
    {
        $this->auth();
       
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (empty($data['note'])) {
            return jsonResponse(['message' => 'Note is required'], 400);
        }
        $ticket = $this->ticketModel->find($ticketId);
        
        if (!$ticket) {
            return jsonResponse(['message' => 'Ticket not found'], 404);
        }
        $user = getAuthenticatedUser();
        $success = $this->ticketModel->addNote($ticketId, $user['user_id'], $data['note']);

        if ($success) {
            return jsonResponse(['message' => 'Note added'], 200);
        } else {
            return jsonResponse(['message' => 'Failed to add note'], 404);
        }
    }
    public function getTickets()
    {
         $this->auth();
        $user = getAuthenticatedUser();
        if (!$this->agentCheck()) {
            $tickets = $this->ticketModel->getAll();
        } else {
            $tickets = $this->ticketModel->getByUser($user['user_id']);
        }

        return jsonResponse([
            'message' => 'success',
            'tickets' => $tickets
        ], 200);
    }
}
