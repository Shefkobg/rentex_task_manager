<?php
function getTaskClass($task)
{
    if ($task['completed']) {
        return 'completed';
    }

    $currentDate = new DateTimeImmutable();
    $dueDate = new DateTimeImmutable($task['due_date'] . ' ' . $task['due_time']);
    $interval = $currentDate->diff($dueDate);

    if ($currentDate > $dueDate) {
        return 'due';
    } elseif ($interval->days <= 3) {
        return 'close-to-due';
    }

    return '';
}
?>
