<?php
require_once("classVehiculo.php");

class Parqueadero {

    //Propiedades
    private $intEspacios;
    private $intNumPisos = 4;
    private $intEspaciosPorPiso = 10;
    public const PRECIO_POR_HORA = '2' ; 

    //Metodos
    public function __construct() {
        $this->intEspacios = array_fill(0, $this->intNumPisos, array_fill(0, $this->intEspaciosPorPiso, null));
        $this->cargarEstado(); 
    }

    public function guardarEstado() {
        
        $datosGuardar = [];
        foreach ($this->intEspacios as $piso => $espaciosPiso) {
            $datosGuardar[$piso] = [];
            foreach ($espaciosPiso as $espacio => $vehiculo) {
                if ($vehiculo !== null) {
                    $cliente = $vehiculo->getCliente();
                    $datosGuardar[$piso][$espacio] = [
                        'strPlaca' => $vehiculo->getPlaca(),
                        'marca' => $vehiculo->getMarca(),
                        'color' => $vehiculo->getColor(),
                        'horaIngreso' => $vehiculo->getHoraIngreso(),
                        'cliente' => [
                            'nombre' => $cliente->getNombre(),
                            'documento' => $cliente->getDocumento(),
                        ]
                    ];
                } else {
                    $datosGuardar[$piso][$espacio] = null;
                }
            }
        }
        file_put_contents('estadoParqueadero.json', json_encode($datosGuardar));
    }

    public function cargarEstado() {
        if (file_exists('estadoParqueadero.json')) {
            $datosGuardados = json_decode(file_get_contents('estadoParqueadero.json'), true);
            foreach ($datosGuardados as $piso => $espaciosPiso) {
                foreach ($espaciosPiso as $espacio => $strVehiculoDatos) {
                    if ($strVehiculoDatos !== null) {
                        $cliente = new Cliente($strVehiculoDatos['cliente']['nombre'], $strVehiculoDatos['cliente']['documento']);
                        $vehiculo = new Vehiculo($strVehiculoDatos['strPlaca'], $strVehiculoDatos['marca'], $strVehiculoDatos['color'], $cliente);
                        $vehiculo->horaIngreso = $strVehiculoDatos['horaIngreso'];
                        $this->intEspacios[$piso][$espacio] = $vehiculo;
                    } else {
                        $this->intEspacios[$piso][$espacio] = null;
                    }
                }
            }
        }
    }

    public function estacionarVehiculo(Vehiculo $vehiculo) {
        for ($piso = 0; $piso < $this->intNumPisos; $piso++) {
            for ($espacio = 0; $espacio < $this->intEspaciosPorPiso; $espacio++) {
                if ($this->intEspacios[$piso][$espacio] === null) {
                    $this->intEspacios[$piso][$espacio] = $vehiculo;
                    $this->guardarEstado();
                    return [$piso + 1, $espacio + 1];  

            }
        }
        return false; }
    }

    public function retirarVehiculo($strPlaca) {
        for ($piso = 0; $piso < $this->intNumPisos; $piso++) {
            for ($espacio = 0; $espacio < $this->intEspaciosPorPiso; $espacio++) {
                if ($this->intEspacios[$piso][$espacio] !== null && $this->intEspacios[$piso][$espacio]->getPlaca() === $strPlaca) {
                    $vehiculo = $this->intEspacios[$piso][$espacio];
                    $horaSalida = time();
                    
                    
                    $intTiempoParqueadero = ($horaSalida - $vehiculo->getHoraIngreso()) / 3600; 
                    $intTiempoParqueadero = ceil($intTiempoParqueadero); 
                    
                    
                    $intCosto = $intTiempoParqueadero * $this-> const = PRECIO_POR_HORA;
                    
                   
                    $this->intEspacios[$piso][$espacio] = null;
                    $this->guardarEstado();

                    
                    return ['costo' => $intCosto, 'tiempo' => $intTiempoParqueadero];
                }
            }
        }
        return false; 
    }

    public function buscarVehiculo($strPlaca) {
        for ($piso = 0; $piso < $this->intNumPisos; $piso++) {
            for ($espacio = 0; $espacio < $this->intEspaciosPorPiso; $espacio++) {
                if ($this->intEspacios[$piso][$espacio] !== null && $this->intEspacios[$piso][$espacio]->getPlaca() === $strPlaca) {
                    return [$piso + 1, $espacio + 1]; 
                }
            }
        }
        return false; 
    }

    public function obtenerEspacios() {
        return $this->intEspacios;
    }
}
?>
