<?php

require_once 'connect.php';
require_once 'Employee.php';
require_once 'Task.php';

class DAO
{

    public PDO $connection;

    public function __construct()
    {
        $this->connection = getConnection();
    }

    public function getEmployees(): array
    {
        $employees = [];

        $stmt = $this->connection->prepare("SELECT * FROM employees");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $first_name = urldecode($row['first_name']);
            $last_name = urldecode($row['last_name']);
            $id = intval(urldecode($row['employee_id']));
            $path = urldecode($row['path']);
            $employees [] = new Employee($first_name, $last_name, $id, $path);
        }

        return $employees;
    }

    public function getTasks(): array
    {
        $tasks = [];

        $stmt = $this->connection->prepare("SELECT * FROM tasks");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $result) {
            $task = urldecode($result['description']);
            $difficulty = intval($result['difficulty']);
            $id = intval(urldecode($result['task_id']));
            $task_status = urldecode($result['task_status']);
            $employee_id = intval(urldecode($result['employee_id']));
            $tasks [] = new Task($task, $difficulty, $id, $task_status, $employee_id);
        }

        return $tasks;
    }

    public function numberOfTasks(): array {
        $tasks = $this->getTasks();
        $employees = $this->getEmployees();

        $array = [];

        foreach ($employees as $employee) {
            $name = $employee->first_name;
            $task_count = 0;
            foreach ($tasks as $task) {
                if ($employee->id === $task->employee_id) {
                    $task_count++;
                }
            }
            $array[$name] = $task_count;
        }
        return $array;
    }

    public function updateEmployee(Employee $employee): array {
        $errors = $this->validateEmployee($employee);

        if (count($errors) != 0) {
            return $errors;
        }

        $stmt = $this->connection->prepare("UPDATE employees 
            SET first_name = :first_name, last_name = :last_name, path = :path
            WHERE employee_id = :employee_id;");

        $stmt->bindValue(':employee_id', urlencode($employee->id));
        $stmt->bindValue(':first_name', urlencode($employee->first_name));
        $stmt->bindValue(':last_name', urlencode($employee->last_name));
        $stmt->bindValue(':path', urlencode($employee->path));
        $stmt->execute();
        return [];
    }

    public function saveEmployee(Employee $employee): array {
        $errors = $this->validateEmployee($employee);

        if (count($errors) != 0) {
            return $errors;
        }

        $stmt = $this->connection->prepare("INSERT INTO 
            employees VALUES (:employee_id ,:first_name, :last_name, :path);");

        $stmt->bindValue(':employee_id', urlencode($employee->id));
        $stmt->bindValue(':first_name', urlencode($employee->first_name));
        $stmt->bindValue(':last_name', urlencode($employee->last_name));
        $stmt->bindValue(':path', urlencode($employee->path));
        $stmt->execute();


        return [];
    }

    public function deleteEmployee(string $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM employees WHERE employee_id = :employee_id");
        $stmt->bindValue(':employee_id', $id);
        $stmt->execute();
        return true;
    }

    public function updateTask(Task $task): array {
        $errors = $this->validateTask($task);

        if (count($errors) != 0) {
            return $errors;
        }

        $stmt = $this->connection->prepare("UPDATE tasks 
            SET task_id = :task_id, description = :description, difficulty = :difficulty, employee_id = :employee_id, 
                task_status = :task_status WHERE task_id = :task_id;");

        $stmt->bindValue(':task_id', urlencode($task->id));
        $stmt->bindValue(':description', urlencode($task->task));
        $stmt->bindValue(':difficulty', urlencode($task->difficulty));
        $stmt->bindValue(':employee_id', urlencode($task->employee_id));
        $stmt->bindValue(':task_status', urlencode($task->task_status));
        $stmt->bindValue(":task_id", urlencode($task->id));
        $stmt->execute();
        return [];
    }

    public function saveTask(Task $task): array {
        $errors = $this->validateTask($task);

        if (count($errors) != 0) {
            return $errors;
        }


        $stmt = $this->connection->prepare("INSERT INTO tasks VALUES 
                      (:task_id, :description, :difficulty, :employee_id, :task_status);");

        $stmt->bindValue(':task_id', urlencode($task->id));
        $stmt->bindValue(':description', urlencode($task->task));
        $stmt->bindValue(':difficulty', urlencode($task->difficulty));
        $stmt->bindValue(':employee_id', urlencode($task->employee_id));
        $stmt->bindValue(':task_status', urlencode($task->task_status));
        $stmt->execute();

        return [];
    }

    private function validateEmployee(Employee $employee): array {
        $array = [];
        if (strlen($employee->first_name) < 1 || strlen($employee->first_name) > 21) {
            $array [] = "First name length must be from 1 to 21 characters!";
        }
        if (strlen($employee->last_name) < 2 || strlen($employee->last_name) > 22) {
            $array [] = "Last name length must be from 2 to 22 characters!";
        }
        return $array;
}
    public function deleteTask(string $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM tasks WHERE task_id = :task_id");
        $stmt->bindValue(':task_id', urlencode($id));
        $stmt->execute();
        return true;
}
    private function validateTask(Task $task): array {
        $array = [];
        if (strlen($task->task) < 5 || strlen($task->task) > 40) {
            $array [] = " Task description length must be from 5 to 40 characters!";
        }
        return $array;
    }
}