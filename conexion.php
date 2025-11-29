<?php
// conexion.php

// DATOS DE CONEXIÓN
// Si cambiaste el puerto en XAMPP al 3307, cambia "localhost" por "localhost:3307"
$servidor = "localhost:3306"; 
$usuario = "root";       
$password = "";          
$base_datos = "dentalife_db"; 

// Crear conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar si hubo error
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Nota: No cerramos la etiqueta PHP aquí para evitar problemas con espacios en blanco