<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/rawphp/api/register' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/UserController.php';
    (new UserController)->register();
}elseif($uri === '/rawphp/api/login' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/UserController.php';
    (new UserController)->login();
}elseif($uri === '/rawphp/api/department/create' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/DepartmentController.php';
    (new DepartmentController)->create();
}elseif($method === 'GET' && $uri === '/rawphp/api/department' ){
    require_once __DIR__ . '/../controllers/DepartmentController.php';
    (new DepartmentController)->getAll();
}elseif($method === 'PUT' && preg_match('#^/rawphp/api/department/update/(\d+)$#', $uri, $matches) ){
    require_once __DIR__ . '/../controllers/DepartmentController.php';
    (new DepartmentController)->update($matches[1]);
}elseif($method === 'POST' && preg_match('#^/rawphp/api/department/delete/(\d+)$#', $uri, $matches)){
    require_once __DIR__ . '/../controllers/DepartmentController.php';
    (new DepartmentController)->delete($matches[1]);
}elseif($uri === '/rawphp/api/ticket/create' && $method === 'POST') {

    require_once __DIR__.'/../controllers/TicketController.php';
    (new TicketController)->create();
}elseif(preg_match('#^/rawphp/api/ticket/assign/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    require_once __DIR__ . '/../controllers/TicketController.php';
    (new TicketController)->assign($matches[1]);
}elseif(preg_match('#^/rawphp/api/ticket/status/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    require_once __DIR__ . '/../controllers/TicketController.php';
    (new TicketController)->updateStatus($matches[1]);
}elseif(preg_match('#^/rawphp/api/ticket/note/(\d+)$#', $uri, $matches) && $method === 'POST') {
    require_once __DIR__ . '/../controllers/TicketController.php';
    (new TicketController)->addNote($matches[1]);
}elseif($uri === '/rawphp/api/tickets' && $method === 'GET') {
    require_once  __DIR__ . '/../controllers/TicketController.php';
    (new TicketController)->getTickets();
}

 


else{
    http_response_code(404);
    echo json_encode(['error' => 'Route Not found']);
};

