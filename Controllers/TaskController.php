<?php

require_once 'models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct($pdo) {
        $this->taskModel = new Task($pdo);
    }

    public function index() {
        $tasks = $this->taskModel->getAllTasks();
        include 'views/tasks.php';
    }

    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['description'], $data['due_date'], $data['due_time'])) {
            $this->taskModel->addTask($data['description'], $data['due_date'], $data['due_time']);
            echo json_encode(['success' => true]);
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->taskModel->deleteTask($_GET['id']);
            echo json_encode(['success' => true]);
        }
    }

    public function saveOrder() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['order']) && is_array($data['order'])) {
            $this->taskModel->updateTaskOrder($data['order']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid order data']);
        }
    }

    public function editDescription() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'], $data['description']) && !empty($data['id']) && !empty($data['description'])) {
            $result = $this->taskModel->updateTaskDescription($data['id'], $data['description']);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid data received']);
        }
    }

    public function toggle() {
        if (isset($_GET['id'])) {
            $this->taskModel->toggleTask($_GET['id']);
            echo json_encode(['success' => true]);
        }
    }
}
?>
