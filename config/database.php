<?php
session_start();

$con = mysqli_connect(
    'localhost',
    'root',
    '',
    'tienda_online'
) or die(mysqli_error($mysql_connect));

?>