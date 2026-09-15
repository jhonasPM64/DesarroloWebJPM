<?php
class Estante {
    public $nivel1;
    public $nivel2;
    public $nivel3;
    public $capacidad;

    public function __construct($capacidad) {
        $this->capacidad = (int)$capacidad;
        $this->nivel1 = [];
        $this->nivel2 = [];
        $this->nivel3 = [];
    }

    private function &obtenerNivel($numNivel) {
        switch ((int)$numNivel) {
            case 1: return $this->nivel1;
            case 2: return $this->nivel2;
            case 3: return $this->nivel3;
            default:
                $vacio = null;
                return $vacio;
        }
    }

    public function insertar($numNivel, $elemento) {
        $nivel = &$this->obtenerNivel($numNivel);
        if ($nivel === null) {
            return "Nivel inválido.";
        }

        if (count($nivel) >= $this->capacidad) {
            return "Nivel $numNivel lleno";
        }

        $nivel[] = trim($elemento);
        return "Elemento '$elemento' insertado en el Nivel $numNivel.";
    }

    public function quitar($numNivel) {
        $nivel = &$this->obtenerNivel($numNivel);
        if ($nivel === null) {
            return "Nivel inválido.";
        }

        if (empty($nivel)) {
            return "El Nivel $numNivel está vacío.";
        }

        $eliminado = array_pop($nivel);
        return "Se quitó '$eliminado' del Nivel $numNivel.";
    }

    public function mostrarEstante() {
        echo "<style>
            .tabla-estante { border-collapse: collapse; margin-top: 10px; }
            .tabla-estante td {
                border: 2px solid #222;
                min-width: 80px;
                height: 40px;
                text-align: center;
                font-family: Arial, sans-serif;
                font-size: 15px;
                padding: 4px 8px;
            }
            .col-nivel { background-color: #d9d9d9; font-weight: bold; }
            .celda-n3 { background-color: #8daed3; }
            .celda-n2 { background-color: #ffc000; }
            .celda-n1 { background-color: #00b050; color: white; }
            .celda-vacia { background-color: #ffffff; }
        </style>";

        echo "<table class='tabla-estante'>";
        $filas = [
            3 => ['datos' => $this->nivel3, 'clase' => 'celda-n3'],
            2 => ['datos' => $this->nivel2, 'clase' => 'celda-n2'],
            1 => ['datos' => $this->nivel1, 'clase' => 'celda-n1']
        ];

        foreach ($filas as $num => $info) {
            echo "<tr>";
            echo "<td class='col-nivel'>Nivel $num</td>";

            foreach ($info['datos'] as $item) {
                echo "<td class='{$info['clase']}'>" . htmlspecialchars($item) . "</td>";
            }

            $vacios = $this->capacidad - count($info['datos']);
            for ($k = 0; $k < $vacios; $k++) {
                echo "<td class='celda-vacia'></td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    }
}
?>