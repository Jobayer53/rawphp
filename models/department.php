<?php



class Department 
{
     private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO department (name) VALUES (?)");
        return $stmt->execute([$name]);
    }
      public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM department WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM department");
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($id, $name) {
        $stmt = $this->db->prepare("UPDATE department SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM department WHERE id = ?");
        return $stmt->execute([$id]);
    }
}