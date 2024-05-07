# Application for task assignment

## Requirements

- must have MySQL database

- Database creation:
```mysql
    CREATE DATABASE IF NOT EXISTS your_database_name;
    
    USE your_database_name;
    
    CREATE TABLE IF NOT EXISTS employees (
         employee_id INT AUTO_INCREMENT PRIMARY KEY,
         first_name VARCHAR(100),
         last_name VARCHAR(100),
         path VARCHAR(255)
    );
    
    CREATE TABLE IF NOT EXISTS tasks (
         task_id INT AUTO_INCREMENT PRIMARY KEY,
         description VARCHAR(255),
         difficulty INT,
         employee_id INT,
         task_status VARCHAR(20),
         FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
    );
```

## How to use
- configure database connection in connect.php file
- run folder with command:
```bash
    php -S localhost:8080 -t employeeSite
```
- Use interactive UI to add, edit, delete task or employee