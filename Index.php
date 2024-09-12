<!-- Incluye los archivos de SweetAlert -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
    h1 {
        text-align: center;
    }
</style>

<h1>Parqueadero el Aguacate</h1>

<?php

require_once("classParqueadero.php");

$parqueadero = new Parqueadero();
$parqueadero->cargarEstado();

define('PRECIO_POR_HORA', 2);  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'estacionar') {
        $cliente = new Cliente($_POST['nombre'], $_POST['documento']);
        $vehiculo = new Vehiculo($_POST['placa'], $_POST['marca'], $_POST['color'], $cliente);
        list($piso, $espacio) = $parqueadero->estacionarVehiculo($vehiculo);
        if ($piso && $espacio) {
            $mensajeVehiculoEstacionado = "Vehículo estacionado en Piso: $piso, Espacio: $espacio";
            echo "<script type='text/javascript'>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '$mensajeVehiculoEstacionado'
            });
        </script>";
        } else {
            echo "<script type='text/javascript'>
            Swal.fire({
                icon: 'error',
                title: 'Sin Espacios',
                text: 'No hay espacios disponibles.'
            });
        </script>";
        }
    }

    if (isset($_POST['accion']) && $_POST['accion'] === 'retirar') {
        $resultado = $parqueadero->retirarVehiculo($_POST['placa_buscar']);
        if ($resultado) {
            $intTiempoParqueadero = $resultado['tiempo'];  
            $costo =  PRECIO_POR_HORA * $intTiempoParqueadero;  

            echo "<script type='text/javascript'>
            Swal.fire({
                icon: 'info',
                title: 'Vehículo Retirado',
                html: 'Costo por estacionamiento: <b>\$$costo</b><br>Tiempo estacionado: <b>$intTiempoParqueadero horas</b>'
            });
        </script>";
        } else {
            $mensajeParaUsuario = "¡Vehículo no encontrado para retirar!";
            echo "<script type='text/javascript'>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '$mensajeParaUsuario'
            });
        </script>";
        }
    }
}

$espacios = $parqueadero->obtenerEspacios();
echo "<h2>Estado del Parqueadero</h2>";
echo "<table border='1' style='width:100%; border-collapse: collapse;  background-color: #90EE90;'>";
for ($piso = 0; $piso < 4; $piso++) {
    echo "<tr><th colspan='10'>Piso " . ($piso + 1) . "</th></tr><tr>";
    for ($espacio = 0; $espacio < 10; $espacio++) {
        if ($espacios[$piso][$espacio] === null) {
            echo "<td style='border: 1px solid black; padding: 5px;'>Libre</td>";
        } else {
            $vehiculo = $espacios[$piso][$espacio];
            $cliente = $vehiculo->getCliente();
            echo "<td style='border: 1px solid black; padding: 5px;'>
                Ocupado<br>
                Placa: {$vehiculo->getPlaca()}<br>
                Marca: {$vehiculo->getMarca()}<br>
                Color: {$vehiculo->getColor()}<br>
                Cliente: {$cliente->getNombre()}<br>
                Documento: {$cliente->getDocumento()}
            </td>";
        }
    }
    echo "</tr>";
}
echo "</table>";
?>

<h2>Estacionar Vehículo</h2>
<form method="POST">
    <input type="hidden" name="accion" value="estacionar">
    <label>Nombre del Cliente:</label><br>
    <input type="text" name="nombre" required><br>
    <label>Documento del Cliente:</label><br>
    <input type="text" name="documento" required><br>
    <label>Placa del Vehículo:</label><br>
    <input type="text" name="placa" required><br>
    <label>Marca:</label><br>
    <input type="text" name="marca" required><br>
    <label>Color:</label><br>
    <input type="text" name="color" required><br>
    <input type="submit" value="Estacionar">
</form>

<h2>Retirar Vehículo</h2>
<form method="POST">
    <input type="hidden" name="accion" value="retirar">
    <label>Placa del Vehículo a Retirar:</label><br>
    <input type="text" name="placa_buscar" required><br>
    <input type="submit" value="Retirar">
</form>
