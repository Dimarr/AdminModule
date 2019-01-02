<?php
/**
 * Created by IntelliJ IDEA.
 * User: drumiantsev
 * Date: 20.12.2018
 * Time: 15:44
 */
function sendAPItoServer($api)
{
    $ini_array = parse_ini_file("options.ini");
    error_reporting(E_ALL);

    //echo "<h2>Соединение TCP/IP</h2>\n";

    /* Получаем порт сервиса WWW. */
    $service_port = $ini_array["port"];//getservbyname('www', 'tcp');

    /* Получаем IP-адрес целевого хоста. */
    $address = $ini_array["address"]; //gethostbyname('www.example.com');

    /* Создаём сокет TCP/IP. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    if ($socket === false) {
        //echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
        return socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        //echo "OK.\n";
    }

    //echo "Пытаемся соединиться с '$address' на порту '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        //echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        return socket_strerror(socket_last_error($socket)) . "\n";
    } else {
      //  echo "OK.\n";
    }

    $in = $api."\r\n"; //"getspbankdetails,68" . "\r\n";
//$in .= "Host: www.example.com\r\n";
//$in .= "Connection: Close\r\n\r\n";
    $out = '';

    //echo "Отправляем HTTP-запрос HEAD...";
    socket_write($socket, $in, strlen($in));
    //echo "OK.\n";

    //echo "Читаем ответ:\n\n";
    $out = socket_read($socket, 8192);
    //echo $out;
    //echo "Закрываем сокет...";
    socket_close($socket);
    //echo "OK.\n\n";
    return $out;
}
?>