<?php
class Ticket
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function create($title, $description, $user_id, $department_id)
    {
        $stmt = $this->db->prepare("INSERT INTO tickets (title, description, user_id, department_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $user_id, $department_id]);
        return $this->db->lastInsertId();
    }

    public function assignTicket($ticketId, $agentId)
    {
        $stmt = $this->db->prepare("UPDATE tickets SET assigned_to = ? WHERE id = ? AND assigned_to IS NULL");
        return $stmt->execute([$agentId, $ticketId]);
    }
    public function updateStatus($ticketId, $status)
    {
        $stmt = $this->db->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $ticketId]);
    }
    public function addNote($ticketId, $userId, $note)
    {
        $stmt = $this->db->prepare("INSERT INTO ticket_notes (ticket_id, user_id, note) VALUES (?, ?, ?)");
        return $stmt->execute([$ticketId, $userId, $note]);
    }
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM tickets");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUser($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
