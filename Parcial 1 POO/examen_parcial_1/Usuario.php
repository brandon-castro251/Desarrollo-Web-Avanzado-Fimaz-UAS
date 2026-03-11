<?php

class Usuario {
    // Atributos privados (encapsulamiento)
    private $nombre;
    private $correo;

    // Constructor con validación de correo
    public function __construct($nombre, $correo) {
        $this->nombre = $nombre;
        
        // Validación de correo (formato válido)
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Correo inválido: " . $correo);
        }
        
        $this->correo = $correo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCorreo() {
        return $this->correo;
    }
}