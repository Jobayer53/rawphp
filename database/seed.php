<?php

require_once 'connection.php';

$db = getDBConnection();

try {
    // Insert departments
    $db->exec("INSERT INTO department (name) VALUES 
        ('Support'), 
        ('Sales'), 
        ('IT'), 
        ('HR')");

    // Insert users
    $db->exec("INSERT INTO users (name, email, password_hash, role) VALUES 
        ('Admin User', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'),
        ('Agent One', 'agent1@example.com', '" . password_hash('agent123', PASSWORD_DEFAULT) . "', 'agent'),
        ('Agent Two', 'agent2@example.com', '" . password_hash('agent123', PASSWORD_DEFAULT) . "', 'agent')");

    // Insert tickets
    $db->exec("INSERT INTO tickets (title, description, user_id, department_id, assigned_to) VALUES 
        ('Login Issue', 'Cannot login to the system.', 2, 1, 3),
        ('Software Installation', 'Need to install MS Office.', 2, 3, NULL)");

    // Insert ticket notes
    $db->exec("INSERT INTO ticket_notes (ticket_id, user_id, note) VALUES 
        (1, 3, 'Checked credentials, seems fine.'),
        (2, 2, 'Waiting for admin approval.')");

    echo "Sample data inserted successfully.";
} catch (PDOException $e) {
    echo "Error seeding database: " . $e->getMessage();
}
