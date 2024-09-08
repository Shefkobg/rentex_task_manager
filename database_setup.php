<?php
function createTasksTable($pdo) {
    $sql = "
    CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255) NOT NULL,
        due_date DATE NOT NULL,
        due_time TIME NOT NULL,
        completed BOOLEAN DEFAULT FALSE,
        sort_order INT NOT NULL
    );
    ";

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
