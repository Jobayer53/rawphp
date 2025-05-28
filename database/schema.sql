-- USERS
CREATE TABLE users(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name TEXT, 
    email TEXT UNIQUE,
    password_hash TEXT,
   role enum('agent','admin') DEFAULT 'agent'
);

-- DEPARTMENT
CREATE TABLE department(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name TEXT
);

-- TICKETS
CREATE TABLE tickets (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    title TEXT,
    description TEXT,
    status enum('open', 'in_progress', 'closed') DEFAULT 'open',
    user_id INTEGER,
    department_id INTEGER,
    assigned_to INTEGER DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);


-- TICKET NOTES
CREATE TABLE ticket_notes (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    ticket_id INTEGER,
    user_id INTEGER,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);