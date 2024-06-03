<?php

// Подключаем зависимости
require_once('db.php');

require_once('functions.php');

// Конфигурация выносится в отдельный файл
require_once('config.php');

// PDO создается единожды в отдном месте
$pdo = new PDO($config['database']['dsn'], $config['database']['username'], $config['database']['password']);

