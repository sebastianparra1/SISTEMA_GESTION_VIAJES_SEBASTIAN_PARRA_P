<?php
$conexion = mysqli_connect('localhost', 'root', '', 'transportes');

if (!$conexion) {
    echo "Error en la conexión a la base de datos";
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM viajes WHERE id = $id";
    $result = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        
        $origen = $row['origen_direccion'] . ", " . $row['origen_comuna'];
        $destino = $row['destino_direccion'] . ", " . $row['destino_comuna'];
        $fecha_viaje = $row['fecha_viaje'];
        $valor = $row['valor'];
        
        
        function obtenerCoordenadas($direccion) {
            $direccion = urlencode($direccion);
            $apiKey = "AIzaSyDxruGtMmG9DFQhuTvLK-tViEAwZyZDSHI"; 
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$direccion&key=$apiKey";

            $respuesta = file_get_contents($url);
            $json = json_decode($respuesta, true);

            if ($json['status'] == 'OK') {
                $lat = $json['results'][0]['geometry']['location']['lat'];
                $lng = $json['results'][0]['geometry']['location']['lng'];
                return ['lat' => $lat, 'lng' => $lng];
            } else {
                return null;
            }
        }

        
        $coordenadas_origen = obtenerCoordenadas($origen);
        $coordenadas_destino = obtenerCoordenadas($destino);

        if (!$coordenadas_origen || !$coordenadas_destino) {
            echo "Error obteniendo coordenadas.";
            exit;
        }
    } else {
        echo "Viaje no encontrado";
        exit;
    }
} else {
    echo "ID no proporcionado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Viaje</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxruGtMmG9DFQhuTvLK-tViEAwZyZDSHI&callback=initMap" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }
        strong {
            color: #000;
        }
        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
    <script>
        function initMap() {
            var origen = { lat: <?php echo $coordenadas_origen['lat']; ?>, lng: <?php echo $coordenadas_origen['lng']; ?> };
            var destino = { lat: <?php echo $coordenadas_destino['lat']; ?>, lng: <?php echo $coordenadas_destino['lng']; ?> };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: origen
            });

            var markerOrigen = new google.maps.Marker({
                position: origen,
                map: map,
                title: 'Origen'
            });

            var markerDestino = new google.maps.Marker({
                position: destino,
                map: map,
                title: 'Destino'
            });

            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            var request = {
                origin: origen,
                destination: destino,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status == 'OK') {
                    directionsRenderer.setDirections(result);

        
        var distancia = result.routes[0].legs[0].distance.text;
        var duracion = result.routes[0].legs[0].duration.text;

        
        var infoDiv = document.createElement('div');
        infoDiv.innerHTML = `<p><strong>Distancia:</strong> ${distancia}</p><p><strong>Tiempo estimado:</strong> ${duracion}</p>`;
        document.body.appendChild(infoDiv);
                } else {
                    alert('No se pudo calcular la ruta');
                }
            });
        }
    </script>
</head>
<body>
    <h1>Detalles del Viaje</h1>
    <p><strong>Fecha de Viaje:</strong> <?php echo htmlspecialchars($fecha_viaje); ?></p>
    <p><strong>Origen:</strong> <?php echo htmlspecialchars($origen); ?></p>
    <p><strong>Destino:</strong> <?php echo htmlspecialchars($destino); ?></p>
    <p><strong>Valor:</strong> <?php echo htmlspecialchars($valor); ?></p>

    <div id="map" style="width: 100%; height: 400px;"></div>
</body>
</html>
