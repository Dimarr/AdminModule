<?php
// routing.php
$Filepath = parse_url($_SERVER['REQUEST_URL'], PHP_URL_PATH);
// ^ это нужно чтобы сервер не искал файл вида /news/?page=2
$File = __DIR__ . '/' . trim($Filepath, '/');
if (file_exists($File)) return False; // файл (или директория) существует, отдаем как есть
$_GET['p'] = $Filepath;
unset($Filepath, $File);
include_once 'index.html';
?>