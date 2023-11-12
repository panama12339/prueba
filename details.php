<?php
include("./config/config.php");
include("./config/database.php");

// Detalles
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->bind_param("i", $id);
        $sql->execute();
        $sql->store_result();

        if ($sql->num_rows > 0) {
            $sql = $con->prepare("SELECT nombre, descripcion, precio FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->bind_param("i", $id);
            $sql->execute();
            $sql->bind_result($nombre, $descripcion, $precio);
            $dir_images = 'images/productos/' . $id . '/';

            $rutaImg = $dir_images . 'pro1.jpg';
            // ...

            if ($sql->fetch()) {
                $dir_images = 'images/productos/' . $id . '/';
                $imagenes = array();

                // Escanea el directorio de imágenes y agrega los nombres de archivos válidos al array $imagenes
                if (is_dir($dir_images)) {
                    $archivos = scandir($dir_images);
                    foreach ($archivos as $archivo) {
                        if ($archivo !== '.' && $archivo !== '..' && (strpos($archivo, '.jpg') !== false || strpos($archivo, '.jpeg') !== false)) {
                            $imagenes[] = $dir_images . $archivo;
                        }
                    }
                } else {
                    // Si el directorio no existe, inicializa $imagenes como un array vacío
                    $imagenes = array();
                }
            } else {
                echo 'No se encontraron resultados para el ID especificado.';
            }

            // ...




            while ($sql->fetch()) {
                // Utiliza $nombre, $descripcion y $precio
            }







        } else {
            echo 'No se encontraron resultados para el ID especificado.';
        }
    } else {
        echo 'Error al procesar la petición. Token no válido.';
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-shopping-basket"></i> groco</a>

        <nav class="navbar">
            <a href="index.html">inicio</a>
            <a href="nosotros.html">Nosotros</a>
            <a href="menu.php">Menu</a>
            <a href="categorias.html">Categorias</a>
            <a href="galeria.html">Galeria</a>
            <a href="blogs.html">Blogs</a>
        </nav>
        <div class="icons">
            <div class="fas fa-bars" id="menu-btn"></div>
            <div class="fas fa-search" id="search-btn"></div>
            <div class="fas fa-shopping-cart" id="cart-btn"></div>
            <!-- Icono del carrito -->

            <div class="fas fa-user" id="login-btn"></div>
        </div>

        <form action="" class="search-form">
            <input type="search" id="search-box" placeholder="search here...">
            <label for="search-box" class="fas fa-search"></label>
        </form>
        <a href="checkout.php" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> <!-- Icono del carrito -->
            <span id="num_cart" class="badge bg-secondary">
                <?php echo $num_cart; ?>
            </span>
        </a>





        <form action="" class="login-form">
            <h3>Inicia Sesion</h3>
            <input type="email" placeholder="introduce tu email" class="box">
            <input type="password" placeholder="introduce tu password" class="box">
            <p>Olvidaste tu contraseña <a href="#">click aqui</a></p>
            <p>No tengo una cuenta <a href="#">Crea ahora</a></p>
            <input type="submit" value="Iniciar Sesion " class="btn">
        </form>
    </header>


    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <img src="<?php echo $rutaImg; ?>" style="max-width: 100%; height: auto;">
                </div>



                <div class="col-md-6 order-md-2">
                    <h2>
                        <?php echo $nombre; ?>
                    </h2>
                    <h2>
                        <?php echo MONEDA . number_format($precio, 2, '.', ','); ?>
                    </h2>
                    <p class="lead">
                        <?php echo $descripcion; ?>

                    </p>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button">Pedir ahora</button>
                        <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo
                            $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>

                    </div>

                </div>

            </div>




        </div>
    </main>

    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php';
            let formData = new FormData();
            formData.append('id', id);
            formData.append('token', token);

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            })
                .then(response => response.json())  // Debes usar .then() en lugar de .the()
                .then(data => {  // Debes usar .then() en lugar de .the()
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart");
                        elemento.innerHTML = data.numero;
                    }
                });
    }
    </script>




    <script src="script.js"></script>
</body>

</html>