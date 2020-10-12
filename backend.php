<html>
<body>

<?php

function sortTable(&$a,$idx){
  for($i=0;$i<count($a);$i++){
    for($j=0;$j<$i;$j++){
      if($a[$i][$idx]<$a[$j][$idx]){
        $t=$a[$i];
        $a[$i]=$a[$j];
        $a[$j]=$t;
      }
    }
  }
}

function groupTable(&$a,$x){
  $lower=10+$x*10;
  $upper=20+$x*10;
  if($upper==50){
    $upper+=10;
  }
  $b=$a;
  $a=[];
  foreach ($b as $e) {
    $s=6;
    if($e[$s]>=$lower && $e[$s]<=$upper){
      array_push($a,$e);
    }
  }
}

function showTable($keys,$a,$q1,$q2){
  if($q2!=0){
    groupTable($a,$q2);
  }
  sortTable($a,$q1);
  echo "<table border=1><tr>";
  $cols=[5,6,7,2,3,1];
  foreach ($cols as $k) {
    echo "<th>$keys[$k]</th>";
  }
  echo "</tr>";
  foreach ($a as $row){
    echo "<tr>";
    foreach ($cols as $k){
      echo "<td>$row[$k]</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}

if(empty($_REQUEST["q1"] || empty($_REQUEST["q2"]))){
  exit();
}
$q1=(int)$_REQUEST["q1"];
$q2=(int)$_REQUEST["q2"];


$p=fopen("pace.csv","r") or die("unable to open");

$pace_keys=explode(',',fgets($p));
array_push($pace_keys,"avg pace");

$a=[];
$i=0;

while(!feof($p)){
  $a[$i]=explode(',',fgets($p));
  foreach ($a[$i] as &$x) $x = (int) $x;
  $a[$i][3]=1000*round($a[$i][1]/$a[$i][2],4);
  $i++;
}

$u=fopen("users.csv","r") or die("unable to open");

$user_keys=explode(',',fgets($u));

$b=[];
$i=0;

while(!feof($u)){
  $b[$i]=explode(',',fgets($u));
  $i++;
}

$keys=array_merge($pace_keys,$user_keys);

$entries=[];
foreach ($a as $e1) {
  foreach ($b as $e2) {
    if($e1[0]==$e2[0]){
      $t=array_merge($e1,$e2);
      array_push($entries, $t);
    }
  }
}


array_push($keys,"sex");
$sex=["n/a","male","male","male","male","male","male","female","male",
"female","female","male","male","female"];
for($i=0;$i<count($entries);$i++){
  array_push($entries[$i],$sex[$i]);
}

showTable($keys,$entries,$q1,$q2);

?>

</body>
</html>