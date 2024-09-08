<?php

require_once 'config.php';
require_once 'database_setup.php';

createTasksTable($pdo);

require_once 'controllers/TaskController.php';
include 'views/helpers.php';

$controller = new TaskController($pdo);

$action = $_GET['action'] ?? 'index';

try {
    switch ($action) {
        case 'add':
            $controller->add();
            break;
        case 'delete':
            $controller->delete();
            break;
        case 'toggle':
            $controller->toggle();
            break;
        case 'editDescription':
            $controller->editDescription();
            break;
        case 'saveOrder':
            $controller->saveOrder();
            break;
        default:
            $controller->index();
            break;
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
