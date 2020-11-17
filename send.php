<?php
require_once 'functions.php';
require_once 'db.php';

date_default_timezone_set('Europe/Moscow');
$name = $_POST['name'];
$tel = $_POST['tel'];
$email = $_POST['email'];
if (empty($name) === true || empty($tel) === true || empty($email) === true) {
    exit("emptyFields");
}
$data = ['name' => $name, 'tel' => $tel, 'email' => $email];
$dateTimeInstance = new DateTime();
$moment = $dateTimeInstance->format('Y-m-d\ H:i:s');
try {
    $connection = new PDO($dsn, $dbuser, $dbpass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (preg_match("/[^а-Яa-zA-z-]/", $name)) {
        exit('Name is cyrillic');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Email is email');
    }

        $name = clear($_POST['name']);
        $tel = clear($_POST['tel']);
        $email = clear($_POST['email']);
        $dateTimeInstance = new DateTime();
        $momentUnix = $dateTimeInstance->getTimestamp();
        $sqlCheckQuery = "SELECT name FROM form WHERE name = '$name' AND email = '$email' AND tel = '$tel' AND time > ('$momentUnix' - '86400') AND time < '$momentUnix'";
        $check = $connection->prepare($sqlCheckQuery);
        $check->execute([$name]);
        $condition = $check->fetchColumn();
        if (gettype($condition) === "string") { // наличие записи с введенным email полученной в течение 24 ч до текущего момента
            exit("order exist");
        } else {
            $momentUnix = $dateTimeInstance->getTimestamp();
            $sql_insert = "INSERT INTO form(name, time, tel, email) VALUES ('$name', '$momentUnix', '$tel', '$email')";
            $insert = $connection->prepare($sql_insert);
            $insert->execute([$name, $momentUnix, $tel, $email]);
            myLog($data, $moment); // log/log.txt
            logXml($data, $moment); // log/log.xml
            logJson($data, $moment); // log/log.json
            $mailMsg = "От: ".$data['name'].PHP_EOL."Тел: ".$data['tel'].PHP_EOL."Email: ".$data['email'].PHP_EOL
                ."Получена: ".$moment.PHP_EOL."Время реакции до: ".date("Y-m-d H:i:s", $momentUnix + 86400);
            mail('jrrtolkin@mail.ru', 'Новая заявка', $mailMsg);
            exit ("ready");
        }
} catch (PDOException $e) {
    error_reporting(E_ALL);
    ini_set('error_log', __DIR__.'/log/errorLog.txt');
    error_log('Запись в лог'.$e, 0);
    error_log('Попытка записи данных'.print_r($data, true), 0);
    $message = "$e" + "Время инцидента" + "$moment";
    mail('jrrtolkin@mail.ru', 'DB "test" problem', "$message");
    exit("DB_Exception");
}
