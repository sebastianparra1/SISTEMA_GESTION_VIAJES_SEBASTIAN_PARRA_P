<?php
$conexion = mysqli_connect('localhost', 'root', '', 'transportes');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == "agregar") {
        $sql = "INSERT INTO viajes (fecha_viaje, origen_direccion, origen_comuna, origen_contacto, destino_direccion, destino_comuna, destino_contacto, usuario_ejecutivo, usuario_solicitante, valor) 
                VALUES ('{$_POST['fecha_viaje']}', '{$_POST['origen_direccion']}', '{$_POST['origen_comuna']}', '{$_POST['origen_contacto']}', '{$_POST['destino_direccion']}', '{$_POST['destino_comuna']}', '{$_POST['destino_contacto']}', '{$_POST['usuario_ejecutivo']}', '{$_POST['usuario_solicitante']}', '{$_POST['valor']}')";
        mysqli_query($conexion, $sql);
    }

    if ($accion == "editar") {
        $field = $_POST['field'];
        $value = $_POST['value'];
        $id = $_POST['id'];
        $sql = "UPDATE viajes SET $field = '$value' WHERE id = '$id'";
        mysqli_query($conexion, $sql);
    }

    if ($accion == "eliminar") {
        $sql = "DELETE FROM viajes WHERE id='{$_POST['id']}'";
        mysqli_query($conexion, $sql);
    }
}
?>
