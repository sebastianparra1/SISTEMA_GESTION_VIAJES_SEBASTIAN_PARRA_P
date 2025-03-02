<?php
$conexion = mysqli_connect('localhost', 'root', '', 'transportes');            
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Viajes</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxruGtMmG9DFQhuTvLK-tViEAwZyZDSHI&callback=initMap" async defer></script>
    <script src="script.js"></script>
</head>
<body>
    <h2>Listado de Viajes</h2>
    <input type="text" id="buscador" placeholder="Buscar viajes..." onkeyup="buscarViajes()">

    
    <form id="form-agregar">
        <input type="text" name="fecha_viaje" placeholder="Fecha de viaje" required>
        <input type="text" name="origen_direccion" placeholder="Origen Dirección" required>
        <input type="text" name="origen_comuna" placeholder="Origen Comuna" required>
        <input type="text" name="origen_contacto" placeholder="Origen Contacto" required>
        <input type="text" name="destino_direccion" placeholder="Destino Dirección" required>
        <input type="text" name="destino_comuna" placeholder="Destino Comuna" required>
        <input type="text" name="destino_contacto" placeholder="Destino Contacto" required>
        <input type="text" name="usuario_ejecutivo" placeholder="Usuario Ejecutivo" required>
        <input type="text" name="usuario_solicitante" placeholder="Usuario Solicitante" required>
        <input type="number" name="valor" placeholder="Valor" required>
        <button type="submit">Agregar Viaje</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Viaje</th>
                <th>Origen Dirección</th>
                <th>Origen Comuna</th>
                <th>Origen Contacto</th>
                <th>Destino Dirección</th>
                <th>Destino Comuna</th>
                <th>Destino Contacto</th>
                <th>Usuario Ejecutivo</th>
                <th>Usuario Solicitante</th>
                <th>Valor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-viajes">
            <?php
            
            $sql = "SELECT * FROM viajes";
            $result = mysqli_query($conexion, $sql);
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr data-id='{$row['id']}'>
                        <td>{$row['id']}</td>
                        <td class='editable' data-field='fecha_viaje'>{$row['fecha_viaje']}</td>
                        <td class='editable' data-field='origen_direccion'>{$row['origen_direccion']}</td>
                        <td class='editable' data-field='origen_comuna'>{$row['origen_comuna']}</td>
                        <td class='editable' data-field='origen_contacto'>{$row['origen_contacto']}</td>
                        <td class='editable' data-field='destino_direccion'>{$row['destino_direccion']}</td>
                        <td class='editable' data-field='destino_comuna'>{$row['destino_comuna']}</td>
                        <td class='editable' data-field='destino_contacto'>{$row['destino_contacto']}</td>
                        <td class='editable' data-field='usuario_ejecutivo'>{$row['usuario_ejecutivo']}</td>
                        <td class='editable' data-field='usuario_solicitante'>{$row['usuario_solicitante']}</td>
                        <td class='editable' data-field='valor'>{$row['valor']}</td>
                        <td>
                            <!-- Enlace para ver detalles -->
                            <a href='detalle.php?id={$row['id']}' class='detalle'>📍 Ver Detalle</a>
                            <!-- Botón de eliminar -->
                            <button class='eliminar' data-id='{$row['id']}'>🗑️</button>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <div id="detalle-viaje" style="display: none;">
        <h3>Detalles del Viaje</h3>
        <p id="origen"></p>
        <p id="destino"></p>
        <p id="distancia"></p>
        <p id="duracion"></p>
        <div id="map" style="width: 100%; height: 400px;"></div>
    </div>
</body>
</html>
