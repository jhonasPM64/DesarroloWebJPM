<?php
$operacion=$_POST['operacion'];
$numero=$_POST['numero'];

echo "<style>
.contenedor{
display: flex;
margin: 40px auto;
flex-direction: column;
width:150px;
padding:10px;
}

table{
border-collapse: collapse;
}

td{
border: 1px solid black;
width:50px;
height:45px;
text-align:center;
}

.cua{
background-color:#D9D9D9;
}
</style>";

echo '<div class="contenedor">';
    echo "tabla de ".$operacion;
    if($operacion=="Suma"){
        $operacion="+";
    }elseif($operacion=="Resta"){
        $operacion="-";
    }elseif($operacion=="Multiplicacion"){
        $operacion="*";
    }else{
        $operacion="/";
    }
    echo "<table>";
    for($i=0; $i<=$numero; $i++){
        echo "<tr>";
        for($j=0; $j<=$numero; $j++){
            if($i==0 && $j==0){
                echo '<td class="cua">'.$operacion.'</td>';
            }elseif($i==0){
                echo '<td class="cua">'.$j.'</td>';
            }elseif($j==0 ){
                echo '<td class="cua">'.$i.'</td>';
            }else{
                echo "<td>";
                switch($operacion){
                    case "+":
                        $resultado=$j+$i;
                        echo $resultado;
                    break;
                    case "-":
                        $resultado=$j-$i;
                        echo $resultado;
                        break;
                    case "*":
                        $resultado=$j*$i;
                        echo $resultado;
                        break;
                    case "/":
                        $resultado=$j/$i;
                        echo number_format($resultado,2);
                        break;
                    default:
                }
                echo"</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
echo '<div>';