<?php
$path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
$dsn = 'mysql:host=localhost;dbname=qassim_clinnic';
$user = 'root';
$pass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
try {
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Failed To Connect ' . $e->getMessage();
}

function getrows($sql)
{
    global $con;
    $getStmt = $con->prepare($sql);

    $getStmt->execute();

    $rows = $getStmt->fetchAll();
    return $rows;
}
function getrowscount($sql)
{
    global $con;
    $getStmt = $con->prepare($sql);

    $getStmt->execute();

    $rows = $getStmt->fetchColumn();
    return $rows;
}
