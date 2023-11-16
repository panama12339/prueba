<?php
require('config/config.php');
require('config/database.php');
require('clases/clienteFunciones.php');

$db = new Database();
$con = $db->conectar();

$error = [];

if (!empty($_POST)) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (Nulo([$nombre, $apellidos, $email, $telefono, $usuario, $password])) {
        $error[] = "Debe llenar los campos";
    }
    if (!emailV($email)) {
        $error[] = "Introduzca una direccion de correo valida";
    }
    if (!psw($password, $repassword)) {
        $error[] = "las contraseñas no coinciden";
    }
    if (UsusarioE($usuario, $con)) {
        $error[] = "ya existe";
    }
    if (CorreoE($email, $con)) {
        $error[] = "Ya existE";
    }
    if (count($error) == 0) {
        // Verificar si las contraseñas coinciden
        if ($password !== $repassword) {
            //$error[] = "Las contraseñas no coinciden.";
        } else {
            // Hash de la contraseña
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            // Generar token
            $token = generarToken();

            // Registrar el cliente
            $id_cliente = registrarCliente([$nombre, $apellidos, $email, $telefono], $con);

            if ($id_cliente > 0) {
                // Registrar el usuario con el ID del cliente
                $id_usuario = registrarUsuario([$usuario, $pass_hash, $token, $id_cliente], $con);

                /* if ($id_usuario > 0) {
                // Éxito
                $error[] = "Usuario registrado correctamente con ID de usuario: " . $id_usuario;
            } else {
                $error[] = "Error al registrar el usuario.";
            }*/
            } else {
                $error[] = "Error al registrar el cliente.";
            }
        }
    }
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <link href="css/estilos.css" rel="stylesheet">



    <!-- for icons  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- bootstrap  -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- for swiper slider  -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

    <!-- fancy box  -->
    <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">
    <!-- custom css  -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-shopping-basket"></i> groco</a>

        <nav class="navbar">
            <a href="#home">inicio</a>
            <a href="nosotros.html">Nosotros</a>
            <a href="menu.html">Menu</a>
            <a href="gallery.html">Galeria</a>
            <a href="blogs.html">Blogs</a>
            <a href="contactanos.html">contactanos</a>
        </nav>
        <div class="shopping-cart">
        </div>

    </header>
    <main>
        <div class="container">
            <h2>Datos del cliente</h2>
            <?php
            Mensajes($error);
            ?>

            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="nombre"><span class="text-danger">*</span> Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                    <input type="email" name="email" id="email" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="password"><span class="text-danger">*</span> Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" require>
                </div>
                <div class="col-md-6">
                    <label for="repassword"><span class="text-danger">*</span> Repetir Contraseña</label>
                    <input type="password" name="repassword" id="repassword" class="form-control" require>
                </div>
                <i>Los campos con * son obligatorios</i>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>