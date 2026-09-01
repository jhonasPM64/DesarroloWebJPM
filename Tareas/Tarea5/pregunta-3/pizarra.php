<?php
class Pizarra {
    private $palabra;
    private $color;
    private $color_fondo;

    public function __construct($palabra, $color, $color_fondo) {
        $this->palabra = $palabra;
        $this->color = $color;
        $this->color_fondo = $color_fondo;
    }
    public function triangulo() {
        $n = strlen($this->palabra);
        echo "<table style='border-collapse: collapse; border: 2px solid black; text-align: center; font-size: 20px; font-family: sans-serif;'>";
        
        for ($i = 1; $i <= $n; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $n; $j++) {
                if ($j < $i) {
                    $letra = $this->palabra[$j];
                    echo "<td style='width: 45px; height: 45px; border: 1px solid black; background-color: {$this->color_fondo}; color: {$this->color};'>$letra</td>";
                } else {
                    echo "<td style='width: 45px; height: 45px; border: 1px solid black; background-color: white;'></td>";
                }
            }
            echo "</tr>";
        }
        
        echo "</table>";
    }
}
?>