<?php
require_once("classCliente.php");

class Vehiculo {
    //Propiedades
    private $strPlaca;
    private $strMarca;
    private $strColor;
    private $strCliente;
    public $intHoraIngreso;

    //Metodos

    public function __construct(string $placa, string $marca, string $color, Cliente $cliente) {
        $this->strPlaca = $placa;
        $this->strMarca = $marca;
        $this->strColor = $color;
        $this->strCliente = $cliente;
        $this->intHoraIngreso = time(); 
    }

    public function getPlaca() {
        return $this->strPlaca;
    }

    public function getMarca() {
        return $this->strMarca;
    }

    public function getColor() {
        return $this->strColor;
    }

    public function getCliente() {
        return $this->strCliente;
    }

    public function getHoraIngreso() {
        return $this->intHoraIngreso;
    }
}
?>
