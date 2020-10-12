<html>
<body>

<?php

function sortTable(&$a,$idx){ // bubble sort is applied
  for($i=0;$i<count($a);$i++){
    for($j=0;$j<$i;$j++){
      if($a[$i][$idx]<$a[$j][$idx]){ // each entry is compared according to given index
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
  if($upper==50){ //group 3 is upper bounded by 60 instead of 50
    $upper+=10; //so extra 10 is added
  }
  $b=$a; 
  $a=[];
  foreach ($b as $e) {
    $s=6; // sixth column is the age attribute of given entry
    if($e[$s]>=$lower && $e[$s]<=$upper){ // If age is within range, entry is appended to table
      array_push($a,$e);
    }
  }
}

function showTable($keys,$a,$q1,$q2){
  if($q2!=0){ // if q2 is 0, everyone is printed
    groupTable($a,$q2);
  }
  sortTable($a,$q1); //sorted according to q1
  echo "<table border=1><tr>";
  $cols=[5,6,7,2,3,1]; //columns that are to be displayed in ui respectively
  foreach ($cols as $k) { //keys are printed
    echo "<th>$keys[$k]</th>";
  }
  echo "</tr>";
  foreach ($a as $row){
    echo "<tr>";
    foreach ($cols as $k){ //attributes of entries are printed
      echo "<td>$row[$k]</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}

if(empty($_REQUEST["q1"] || empty($_REQUEST["q2"]))){ 
  exit();
}

$q1=(int)$_REQUEST["q1"]; //q1 keeps sorting parameter
$q2=(int)$_REQUEST["q2"]; //q2 keeps grouping parameter


$p=fopen("pace.csv","r") or die("unable to open");

$pace_keys=explode(',',fgets($p)); // first line of the file is name of columns 
array_push($pace_keys,"avg pace"); // "avg pace" does not exist, so it is added manually

$a=[];
$i=0;

while(!feof($p)){ //reading until file ends
  $a[$i]=explode(',',fgets($p)); // next line is read and appended to variable "a". Attributes are distributed to columns
  foreach ($a[$i] as &$x) $x = (int) $x; // its attributes are casted to int
  $a[$i][3]=1000*round($a[$i][1]/$a[$i][2],4); // average pace is calculated and assigned to a new column of entry
  $i++; 
}

$u=fopen("users.csv","r") or die("unable to open"); // Same operations are applied to other file (except average pace calculation)

$user_keys=explode(',',fgets($u)); 

$b=[];
$i=0;

while(!feof($u)){
  $b[$i]=explode(',',fgets($u));
  $i++;
}

$keys=array_merge($pace_keys,$user_keys); // keys of each file is merged

$entries=[]; // entries will keep combination of entries of both files
foreach ($a as $e1) {
  foreach ($b as $e2) {
    if($e1[0]==$e2[0]){ // Checking if both entry has same user_id. if true, then they are merged and appended to entries
      $t=array_merge($e1,$e2);
      array_push($entries, $t);
    }
  }
}

// entries have no sex info, so it is added to keys and each entry manually
array_push($keys,"sex"); // added to keys
$s=["n/a","male","male","male","male","male","male","female","male",
"female","female","male","male","female"]; 
for($i=0;$i<count($entries);$i++){
  array_push($entries[$i],$s[$i]); // added to entries
}

showTable($keys,$entries,$q1,$q2); // This function sorts and groups, first. Then, it prints the keys and entries in table format

?>

</body>
</html>