<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Задачи</title>
</head>

<body>
    <div class="container">
        <div class="task-input">
            <input type="text" id="new-task" placeholder="Въведи описание на задачата">
            <input type="date" id="due-date">
            <input type="time" id="due-time">
            <button id="add-task">Добави задача</button>
        </div>
        <ul id="task-list">
            <?php
            usort($tasks, function ($a, $b) {
                return strtotime($a['due_date'] . ' ' . $a['due_time']) - strtotime($b['due_date'] . ' ' . $b['due_time']);
            });

            foreach ($tasks as $task):
                $taskClass = getTaskClass($task); ?>
                <li class="<?php echo $taskClass; ?>" data-id="<?php echo htmlspecialchars($task['id']); ?>" draggable="true">
                    <input type="checkbox" class="toggle-complete" <?php echo $task['completed'] ? 'checked' : ''; ?> >
                    <span class="task-desc" contenteditable="true" data-id="<?php echo htmlspecialchars($task['id']); ?>">
                        <?php echo htmlspecialchars($task['description']); ?>
                    </span>
                    <span> До: <?php echo htmlspecialchars($task['due_date']) . ' ' . htmlspecialchars(date('H:i', strtotime($task['due_time']))); ?></span>
                    <button class="delete-task" title="Изтрий"><i class="fas fa-trash"></i></button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="public/js/app.js"></script>
</body>

</html>
