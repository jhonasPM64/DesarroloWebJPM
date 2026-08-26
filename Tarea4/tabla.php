<?php
$tabla=$_POST['tabla'];
$numero=$_POST['numero'];

echo "tabla de ".$tabla;
echo "<style>
body{
font-family: sans-serif;

}
td{
border: 1px solid black;
height: 20px;
width: 50px;
text-align: center;
}
table{
border-collapse: collapse;
}
.verde{
background-color: #F79646;
}

</style>";
$total=0;
echo "<table>";
for($i=0; $i<=$numero; $i++){
    echo "<tr>";
    for($j=0; $j<=$numero; $j++){
        if($i==0 && $j==0){
        echo '<td class="verde"></td>';
        }elseif($i==0){
        echo '<td class="verde">'.$j.'</td>';
        }elseif($j==0){
        echo '<td class="verde">'.$i.'</td>';
        }else{
        switch ($tabla) {
            case "suma":
                $total = $i + $j;
                break;
            case "resta":
                $total = $j - $i;
                break;
            case "multiplicacion":
                $total = $j * $i;
                break;
            case "division":
                $total = round($j / $i, 2);
                break;
            default:
                $total = 0;
            }
            echo '<td>' . $total . '</td>';
        }
    }
    echo "</tr>";
}
