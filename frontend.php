<html>
<head>
<script>
function showHint(str,str2) {
  if (str.length == 0) {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "frontend.php?q1=" + str + "&q2=" + str2, true);
    xmlhttp.send();
  }
}


</script>
</head>
<body onload="showHint(document.getElementById('sort').value,document.getElementById('group').value)">

<form>
  <b>Sort by:</b><select id="sort" onchange="showHint(this.value,document.getElementById('group').value)">
    <option value="3">average pace</option>
    <option value="2">distance</option>
    <option value="1">total time</option>
  </select>
  <b>Group by age:</b><select id="group" onchange="showHint(document.getElementById('sort').value,this.value)">
    <option value="0">Everyone</option>
    <option value="1">20-30</option>
    <option value="2">30-40</option>
    <option value="3">40-60</option>
  </select>
</form>
<br>
<span id="txtHint"><b>Select one pls..</b></span>
</body>
</html>