<?php
$palabras = $_GET['palabras'];
$guion = $_GET['guion'];

$letras = mb_str_split($palabras); 
$total=count($letras);
$separador=str_repeat("-", $guion);

echo "<style>
.contenedor{
display:flex;
flex-direction: column;
padding:10px 15px;
gap:20px;
margin: 100px auto;
border:1px solid black;
width:300px;
background-color: white;
}

body{
background-color:wheat;
}

table{
border-collapse: collapse;
}

.primero{
border: 1px solid black;
text-align: center;
white-space: nowrap;
padding:10px 15px;
}

.rojo{
border: 1px solid black;
width: 30px;
height: 30px;
text-align: center;
background-color:red;
}
.amarillo{
border: 1px solid black;
width: 30px;
height: 30px;
text-align: center;
background-color:yellow;
}
.verde{
border: 1px solid black;
width: 30px;
height: 30px;
text-align: center;
background-color:green;
}
</style>";

echo '<div class="contenedor">';
    echo "<div>";
        echo "Inciso A"."<br>";
        $separada = implode($separador, $letras);
        echo "cadena original: ".$palabras."<br>";
        echo "cadena separada: ".$separada;
    echo "</div>";

    echo "<div>";
        echo "Inciso B"."<br>";
        echo "<table><tr>";
        echo "<tr>";
        foreach ($letras as $i=>$letra) {
            if($i%3==0){
                echo '<td class="rojo">'.$letra.'</td>';
            }elseif($i%3==1){
                echo '<td class="amarillo">'.$letra.'</td>';
            }else{
                echo '<td class="verde">'.$letra.'</td>';
            }
        }
        echo "</tr></table>";
    echo '</div>';

    echo "<div>";
        echo "Inciso C"."<br>";
        echo "cadena invertida: ".implode('',array_reverse($letras));
    echo "</div>";
echo "</div>";
?>