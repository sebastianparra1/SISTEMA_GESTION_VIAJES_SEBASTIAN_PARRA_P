$(document).ready(function () {
    
    function buscarViajes() {
        let input = $("#buscador").val().toLowerCase();
        $("#tabla-viajes tr").each(function () {
            let textoFila = $(this).text().toLowerCase();
            $(this).toggle(textoFila.includes(input));
        });
    }

    
    $("#buscador").on("input", buscarViajes);
    
    $(".editable").click(function () {
        var currentText = $(this).text();
        var field = $(this).data("field");
        var id = $(this).closest("tr").data("id");

        var inputField = $("<input>").val(currentText).attr("type", "text").attr("data-field", field).attr("data-id", id);
        $(this).html(inputField);
        inputField.focus();

        inputField.blur(function () {
            var newValue = $(this).val();
            var field = $(this).data("field");
            var id = $(this).data("id");

            $.post("ajax.php", { id: id, field: field, value: newValue, accion: "editar" }, function () {
                $(this).parent().html(newValue);
            }.bind(this));
        });
    });

    
    $(".eliminar").click(function () {
        if (confirm("¿Eliminar este viaje?")) {
            $.post("ajax.php", { id: $(this).data("id"), accion: "eliminar" }, function () {
                location.reload();
            });
        }
    });

    
    $("#form-agregar").submit(function (event) {
        event.preventDefault();
        $.post("ajax.php", $(this).serialize() + "&accion=agregar", function () {
            location.reload();
        });
    });

    
    $(".detalle").click(function () {
        var id = $(this).data("id");
        $.get("detalle.php", { id: id }, function (data) {
            var viaje = JSON.parse(data);
            $("#origen").text("Origen: " + viaje.origen_direccion + ", " + viaje.origen_comuna);
            $("#destino").text("Destino: " + viaje.destino_direccion + ", " + viaje.destino_comuna);
            $("#detalle-viaje").fadeIn();
            initMap(viaje.origen_direccion + ", " + viaje.origen_comuna, viaje.destino_direccion + ", " + viaje.destino_comuna);
        });
    });
});


function initMap(origen, destino) {
    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: -33.4489, lng: -70.6693 }, 
    });
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    var request = {
        origin: origen,
        destination: destino,
        travelMode: "DRIVING",
    };
    directionsService.route(request, function (result, status) {
        if (status === "OK") {
            directionsRenderer.setDirections(result);
            var distancia = result.routes[0].legs[0].distance.text;
            var duracion = result.routes[0].legs[0].duration.text;
            $("#distancia").text("Distancia: " + distancia);
            $("#duracion").text("Tiempo estimado: " + duracion);
        }
    });
}
