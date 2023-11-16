<?php
class Database
{
    private $hostname = "localhost";
    private $database = "analisis";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    private $pdo;

    public function conectar()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname . ";dbname=" . $this->database . ";charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $this->pdo;
        } catch (PDOException $e) {
            // Puedes lanzar una excepción para manejar el error de manera más elegante
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }
    }

    public function estaConectada()
    {
        // Utiliza $this->pdo en lugar de $pdo para referenciar la propiedad de la clase
        return $this->pdo instanceof PDO;
    }
}
