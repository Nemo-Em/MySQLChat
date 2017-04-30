<?php

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "newyork-13";
$DB_DBNAME = "MySQLForum";

$conn = new mysqli
($DB_HOST,
$DB_USER,
$DB_PASSWORD,
$DB_DBNAME);

if ($conn->connect_error) {
die("Połączenie nieudane. Błąd: " .
$conn->connect_error);
}
