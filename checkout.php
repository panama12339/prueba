<?php
include("config/config.php");
include("config/database.php");


$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    $productosIds = array_keys($productos);
    $idsString = implode(",", $productosIds);

    $query = "SELECT id, nombre, precio FROM productos WHERE id IN ($idsString) AND activo=1";

    $result = mysqli_query($con, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $nombre = $row['nombre'];
            $precio = $row['precio'];
            $cantidad = $productos[$id]; // Aquí obtienes la cantidad desde la sesión

            // Agrega el producto al array $lista_carrito
            $lista_carrito[] = [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad
            ];
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($con);
    }
}

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
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>productos</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan="5" class="text-cemter"><b>Lista vacia</b></td></tr>';
                        } else {
                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                                $precio = $producto['precio'];
                                $cantidad = $producto['cantidad'];
                                $subtotal = $cantidad * $precio;
                                $total += $subtotal;

                                ?>
                                <tr>
                                    <td>
                                        <?php echo $nombre; ?>
                                    </td>
                                    <td>
                                        <?php echo MONEDA . number_format($precio, 2, '.', ','); ?>
                                    </td>
                                    <td>
                                        <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5"
                                            id="cantidad_<?php echo $_id; ?>"
                                            onchange="actualizaCantidad(this.value, <?php echo $_id ?>)">
                                    </td>
                                    <td>
                                        <div id="subtotal_<?php  echo $_id; ?>" name="subtotal[]">
                                            <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                                        </div>
                                    </td>
                                    <td><a id="elimiar" class="btn btn-warning btn-sm" data-bs-id="<?php echo
                                        $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar </a></td>

                                </tr>
                                <?php } ?>
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                                    <p class="h3" id="total">
                                        <?php echo MONEDA . number_format( $total,2,  '.',  ','
                                        ); ?>
                                    </p>
                                </td>


                            </tr>
                        </tbody>
                    <?php } ?>
                </table>


            </div>
            <div class="row">
                <div class="col-md-5 offset-md-7 d-grid gap-2">
                    <button class="btn btn-primary btn-lg">Realizar pago</button>
                </div>

            </div>

        </div>

    </main>
    <!-- Modal -->

    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea elminar el producto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="elimana()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    <script>
    

        function actualizaCantidad(cantidad, id) {
            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action','agregar');
            formData.append('id', id);
           
            formData.append('cantidad', cantidad);

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            })
                .then(response => response.json())  
                .then(data => {  
                    if (data.ok) {
                        
                    }
                })
    }
    </script>




    <script src="script.js"></script>
</body>

</html>
?>
