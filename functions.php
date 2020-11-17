<?php
function clear(string $var) {
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
function myLog(array $data, $time) {
    $moment = $time;
    $logRow = $moment . "В БД записаны данные : " .  print_r($data, true);
    file_put_contents(__DIR__ . '/log/log.txt', $logRow, FILE_APPEND);
}
function logXml(array $array, $date) {
    $dom = new DomDocument();
    $dom->load("log/log.xml");

    $xpath = new DOMXPath ($dom);
    $parent = $xpath->query ('//root');
    $next = $xpath->query ('//root/row');

    $name  = $dom->createElement('name', $array['name']);
    $tel   = $dom->createElement('tel', $array['tel']);
    $email = $dom->createElement('email', $array['email']);
    $row = $dom->createElement('row');
    $row->setAttribute('date', $date);
    $row->appendChild($name);
    $row->appendChild($tel);
    $row->appendChild($email);

    $parent->item(0)->insertBefore($row, $next->item(0));
    $dom->save("log/log.xml");
}

function logJson(array $array, $date) {
    $file = file_get_contents("log/log.json");
    $array['date'] = $date;
    $decode = json_decode($file, true);
    $decode[] = array();
    array_push($decode, $array);
    file_put_contents("log/log.json", json_encode($decode));
}
