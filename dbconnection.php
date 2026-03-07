<?php
$host = 'sql211.infinityfree.com';
$db   = 'if0_41118484_banlinhkienmaytinh';
$user = 'if0_41118484';
$pass = 'pd4AvQf18GxB';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Ket noi that bai";
}
?>