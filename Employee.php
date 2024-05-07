<?php

class Employee
{
    public string $first_name;

    public string $last_name;

    public int $id;

    public string $path;

    public function __construct(string $first_name, string $last_name, int $id, string $path)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->id = $id;
        $this->path = $path;
    }

}