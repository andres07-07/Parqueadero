<?php
class Cliente {
    //Propiedades
    private $strNombre;
    private $intDocumento;

    //Metodos
    public function __construct(string $nombre, int $documento) {
        $this->strNombre = $nombre;
        $this->intDocumento = $documento;
    }

    public function getNombre() {
        return $this->strNombre;
    }

    public function getDocumento() {
        return $this->intDocumento;
    }
}
?>
