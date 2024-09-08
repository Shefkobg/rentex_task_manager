document.addEventListener('DOMContentLoaded', function() {
    const addTaskBtn = document.getElementById('add-task');
    const newTaskInput = document.getElementById('new-task');
    const dueDateInput = document.getElementById('due-date');
    const dueTimeInput = document.getElementById('due-time');
    const taskList = document.getElementById('task-list');

    addTaskBtn.addEventListener('click', function() {
        const taskDescription = newTaskInput.value.trim();
        const dueDate = dueDateInput.value;
        const dueTime = dueTimeInput.value;

        if (taskDescription && dueDate && dueTime) {
            fetch('index.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ description: taskDescription, due_date: dueDate, due_time: dueTime })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });

    taskList.addEventListener('change', function(event) {
        if (event.target.classList.contains('toggle-complete')) {
            const taskId = event.target.closest('li').dataset.id;
            fetch(`index.php?action=toggle&id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    });

    taskList.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-task')) {
            const taskId = event.target.closest('li').dataset.id;
            fetch(`index.php?action=delete&id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    });

    let draggedItem = null;

    taskList.addEventListener('dragstart', function(event) {
        if (event.target.tagName === 'LI') {
            draggedItem = event.target;
            event.target.style.opacity = '0.5';
        }
    });

    taskList.addEventListener('dragend', function(event) {
        if (event.target.tagName === 'LI') {
            event.target.style.opacity = '1';
        }
        draggedItem = null;
    });

    taskList.addEventListener('dragover', function(event) {
        event.preventDefault();
    });

    taskList.addEventListener('drop', function(event) {
        event.preventDefault();
        if (event.target.tagName === 'LI' && draggedItem) {
            const bounding = event.target.getBoundingClientRect();
            const offset = event.clientY - bounding.top;
            if (offset > bounding.height / 2) {
                taskList.insertBefore(draggedItem, event.target.nextSibling);
            } else {
                taskList.insertBefore(draggedItem, event.target);
            }
            saveNewOrder();
        }
    });

    function saveNewOrder() {
        const taskIds = Array.from(taskList.children).map(li => li.dataset.id);
        fetch('index.php?action=saveOrder', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order: taskIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Order saved successfully');
            }
        });
    }

    taskList.addEventListener('focusout', function(event) {
        if (event.target.classList.contains('task-desc')) {
            const taskId = event.target.dataset.id;
            const newDescription = event.target.textContent.trim();

            if (taskId && newDescription) {
                fetch('index.php?action=editDescription', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: taskId, description: newDescription })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to update description: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    });
});
