<?php

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/department.php';

class departmentController
{
    private $pdo;
    private $departmentModel;
    public function __construct()
    {
        $this->pdo = getDBConnection();
        $this->departmentModel = new Department($this->pdo);
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
    public function create()
    {

        if ($this->adminCheck()) {

            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['name']) || empty($data['name'])) {
                return jsonResponse(['error' => 'Name is required'], 400);
            };

            $name = $data['name'];
            $this->departmentModel->create($name);
            return jsonResponse(['message' => 'success', 'id' => $this->pdo->lastInsertId(), 'name' => $name], 200);
            
        } else {
            return jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }
    public function getAll() {
        if ($this->adminCheck()) {
            $departments = $this->departmentModel->getAll();
            return jsonResponse(['message' => 'success', 'departments' => $departments], 200);
        } else {
            return jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }
    public function update($id)
    {
         if ($this->adminCheck()) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['name']) || empty($data['name'])) {
                return jsonResponse(['error' => 'Name is required'],);
            };
            $deparment = $this->departmentModel->find($id);
            if (!$deparment) {
                return jsonResponse(['error' => 'Department not found'], 404);
            }
            $this->departmentModel->update($id, $data['name']);


            return jsonResponse(['message' => 'Department updated successfully'], 200);
        } else {
            return jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }
    public function delete($id) {
         if ($this->adminCheck()) {
             $deparment = $this->departmentModel->find($id);
             if (!$deparment) {
                 return jsonResponse(['error' => 'Department not found'], 404);
             }
             $this->departmentModel->delete($id);
             return jsonResponse(['message' => 'Department deleted successfully'], 200);
         }else{
             return jsonResponse(['error' => 'Unauthorized'], 401);
         }
    }
}
