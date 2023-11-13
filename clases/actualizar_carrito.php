<?php
include("config/config.php");
include("config/database.php");

$datos = array();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if ($respuesta !== false) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = json_encode(number_format($respuesta, 2, '.', ','));
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

function agregar($id, $cantidad) {
    $res = 0;
    if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            // Establecer la conexión a la base de datos
            $con = mysqli_connect(
                'localhost',
                'root',
                '',
                'tienda_online'
            ) ;
            

            if ($con) {
                $sql = "SELECT precio FROM productos WHERE id = ? AND activo = 1 LIMIT 1";
                $stmt = mysqli_prepare($con, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $precio);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    if ($precio !== null) {
                        $res = $cantidad * $precio;
                    }
                }

                mysqli_close($con);
                return $res;
            }
        }
    }
    return false; // Devolver false si hay un problema en la conexión a la base de datos
}

// Devolvemos la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($datos);
?>
