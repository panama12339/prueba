<?php
include("./config/config.php");
include("./config/database.php");

$sql = "SELECT id, nombre, precio FROM productos WHERE activo=1";
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Procesa cada fila de resultados aquí
        // Puedes acceder a los valores de las columnas como $row['nombre_de_columna']
    }
} else {
    echo "Error en la consulta: " . mysqli_error($con);
}
//session_destroy();
print_r($_SESSION);
mysqli_close($con);

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
             <!-- Icono del carrito-->
            
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
        <div class="container" class="d-block w-100">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($result as $row) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php
                            $id = $row['id'];
                            $imagen = "images/productos/" . $id . "/pro1.jpg";
                            if (!file_exists($imagen)) {
                                $imagen = "images/no-photo.jpg";
                            }
                            ?>
                            <img src="<?php echo $imagen; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                            <!-- Establece el ancho y alto deseados en el estilo de la imagen -->
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $row['nombre']; ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo number_format($row['precio'], 2, '.', ','); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"
                                            class="btn btn-primary">Detalles</a>
                                    </div>
                                    <button class="btn btn-outline-primary" type="button" onclick="addProducto
                                    (<?php echo  $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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