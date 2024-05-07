<?php
//
//
//const HOST = 'localhost';
//const USERNAME = 'employeedb';
//
//const DBNAME = 'database';
//
//const PASSWORD = '33de19';
//
//function getConnection(): PDO {
//    ;
//
//    $address = sprintf('mysql:host=%s;port=3306;dbname=%s',
//        HOST, DBNAME);
//
//    return new PDO($address, USERNAME, PASSWORD);
//}


const HOST = 'localhost';
const USERNAME = 'username';

const DBNAME = 'database';

const PASSWORD = 'password';

const PORT = 'port';

function getConnection(): PDO
{
    ;

    $address = sprintf('mysql:host=%s;port=%s;dbname=%s',
        HOST, PORT, DBNAME);

    return new PDO($address, USERNAME, PASSWORD);
}
