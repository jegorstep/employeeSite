<?php
    require_once 'functions.php';
    require_once 'DAO.php';
    require_once 'vendor/tpl.php';
    ini_set('display_errors', 0);

const MAX_UPLOAD_SIZE = 1000 * 1024; // set upload size


    $command = $_GET['cmd'];
    $info = new DAO();
    $tasks = $info->getTasks();
    $employees = $info->getEmployees();
    $data = [];
    $path = '';
    $errors = [];
    $messages = [];


if (isset($_FILES['picture'])) {
    if ($_FILES['picture']["size"] <= MAX_UPLOAD_SIZE) {
        $extension = pathinfo($_FILES['picture']["name"], PATHINFO_EXTENSION);

        $path = 'temp/' . 'pic' . $_GET['id'] . '.' . $extension;
        move_uploaded_file($_FILES['picture']["tmp_name"], $path);
    }
}

    $validation = $_GET['validate'];

    if ($_GET['save'] === 'success') {
        $messages [] = 'Saved successfully';
    } else if ($_GET['save'] === 'delete') {
        $messages [] = 'Deleted succesfully';
    }

    if ($validation === 'true') {
        if ($command ==='employee-edit') {
            if (isset($_POST['deleteButton'])) {
                $info->deleteEmployee($_GET['id']);
                header('Location: index.php?cmd=employee-list&save=delete');
                exit();
            } else {
                $employee = new Employee($_POST['firstName'] ?? '', $_POST['lastName'] ?? '',
                    $_GET['id'] ?? $_POST['id'], $path);
                $errors = $info->updateEmployee($employee);
                if ($errors === []) {
                    header('Location: index.php?cmd=employee-list&save=success');
                    exit();
                }
            }
        } else if ($command === 'employee-form') {
            $employee = new Employee($_POST['firstName'] ?? '', $_POST['lastName'] ?? '',
                $info->connection->lastInsertId(), $path);
            $errors = $info->saveEmployee($employee);
            if ($errors === []) {
                header('Location: index.php?cmd=employee-list&save=success');
                exit();

            }
        } else if ($command === 'task-edit') {
            if (isset($_POST['deleteButton'])) {
                $info->deleteTask($_GET['id']);
                header('Location: index.php?cmd=task-list&save=delete');
                exit();
            } else {
                if (isset($_POST['isCompleted'])) {
                    $task_status = 'Closed';
                } else if (intval($_POST['employeeId']) > 0) {
                    $task_status = 'Pending';
                }
                $task = new Task($_POST['description'] ?? '', intval($_POST['estimate']) ?? 0,
                    intval($_GET['id']), $task_status, intval($_POST['employeeId'])?? 0);
                $errors = $info->updateTask($task);
                if ($errors === []) {
                    header('Location: index.php?cmd=task-list&save=success');
                    exit();
                }
            }
        } else if ($command === 'task-form') {
            $task_status = 'Open';
            if (intval($_POST['employeeId']) > 0) {
                $task_status = 'Pending';
            }
            $task = new Task($_POST['description'] ?? '', intval($_POST['estimate']) ?? 0,
                $info->connection->lastInsertId(), $task_status, intval($_POST['employeeId']) ?? 0);

            $errors = $info->saveTask($task);
            if ($errors === []) {
                header('Location: index.php?cmd=task-list&save=success');
                exit();
            }
        }
    }
    $tasks = $info->getTasks();
    $employees = $info->getEmployees();

    if ($command === 'employee-list') {
        $data = ['employees' => $employees, 'messages' => $messages];
        print renderTemplate('html/employee-list.html', $data);
    }
    else if ($command === 'task-list') {
        $data  = ['tasks' => $tasks, 'messages' => $messages];
        print renderTemplate('html/task-list.html', $data);
    }
    else if ($command === 'task-form') {
        $data = ['errors' => $errors, 'employees' => $employees];
        print renderTemplate('html/task-form.html', $data);
    }
    else if ($command === 'employee-form') {
        $data = ['tasks' => $tasks, 'errors' => $errors];
        print renderTemplate('html/employee-form.html', $data);
    }
    else if ($command === 'employee-edit') {
        $id = intval($_GET['id']);
        $worker = null;
        foreach ($employees as $employee) {
            if ($id === $employee->id) {
                $worker = $employee;
                break;
            }
        }
        $data = ['employee' => $worker, 'errors' => $errors];
        print renderTemplate('html/employee-edit.html', $data);
    }
    else if ($command === 'task-edit') {
        $id = intval($_GET['id']);
        $task = null;
        $employee_name = "";
        $worker = "";
        foreach ($tasks as $t) {
            if ($id === $t->id) {
                $task = $t;
            }
        }
        if ($task->employee_id != null) {
            foreach ($employees as $employee) {
                if ($employee->id === $task->employee_id) {
                    $worker = $employee;
                }
            }
        }
        $data = ['task' => $task, 'employee' => $worker, 'employees' => $employees, 'errors' => $errors];
        print renderTemplate('html/task-edit.html', $data);
    }
    else {
        $number = $info->numberOfTasks();
        $data = ['employees' => $employees, 'tasks' => $tasks, 'number' => $number];
        print  renderTemplate('html/dashboard.html', $data);
    }


