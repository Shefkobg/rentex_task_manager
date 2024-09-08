<?php

require_once 'config.php';

class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllTasks() {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks ORDER BY sort_order ASC, due_date ASC, due_time ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTask($description, $due_date, $due_time) {
        $stmt = $this->pdo->query("SELECT MAX(sort_order) AS max_order FROM tasks");
        $maxOrder = $stmt->fetch(PDO::FETCH_ASSOC)['max_order'];
        $stmt = $this->pdo->prepare("INSERT INTO tasks (description, due_date, due_time, sort_order) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$description, $due_date, $due_time, $maxOrder + 1]);
    }

    public function deleteTask($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateTaskOrder($order) {
        foreach ($order as $index => $taskId) {
            $stmt = $this->pdo->prepare("UPDATE tasks SET sort_order = ? WHERE id = ?");
            $stmt->execute([$index, $taskId]);
        }
    }

    public function toggleTask($id) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET completed = NOT completed WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateTaskDescription($id, $description) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET description = ? WHERE id = ?");
        return $stmt->execute([$description, $id]);
    }
}
?>
