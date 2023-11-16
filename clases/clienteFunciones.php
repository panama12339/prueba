<?php
function generarToken()
{
    return md5(uniqid(mt_rand(), false));
}

function registrarCliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clientes (nombre, apellidos, correo, telefono, estado, fecha_C) VALUES (?,?,?,?,1,now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}


function Nulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function emailV($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function psw($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}
function registrarUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (nombre, password, token, id_cliente) VALUES (?,?,?,?)");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}


function UsusarioE($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE nombre LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true;
    } else {
        return false;
    }
}

function CorreoE($correo, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE correo LIKE ? LIMIT 1");
    $sql->execute([$correo]);
    if ($sql->fetchColumn() > 0) {
        return true;
    } else {
        return false;
    }
}


function Mensajes(array $error)
{
    if (count($error) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($error as $e) {
            echo '<li>' . $e . '</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function login($user, $psw, $con)
{
    $sql = $con->prepare("SELECT id, nombre, password FROM usuarios WHERE nombre LIKE ? LIMIT 1");
    $sql->execute([$user]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($psw, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            header("Location: index.php");
            exit;
        }
    }
    return 'Credenciales Incorrectas';
}
