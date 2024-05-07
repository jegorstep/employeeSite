<?php

class Task
{
    public string $task;
    public int $difficulty;
    public int $id;
    public string $task_status;

    public int $employee_id;

    public function __construct(string $task, int $difficulty, int $id, string $task_status, int $employee_id)
    {
        $this->task = $task;
        $this->difficulty = $difficulty;
        $this->id = $id;
        $this->task_status = $task_status;
        $this->employee_id = $employee_id;
    }

}