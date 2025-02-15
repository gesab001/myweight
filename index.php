<html>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<style>
table, th, td {
  border: 1px solid black;
}
</style>
<body>
 
<form action="index.php" method="post" name="loginForm" target="_self">
<label for="request">Request: </label>
<input autofocus required type="text" id="request" name="request" required><br>

<input type="submit" value="Submit">
</form>

<canvas id="myChart" style="width:100%;max-width:700px"></canvas>


</body>

<script>

function plotData(jsonArray){
	var jsonArray = JSON.parse(jsonArray);
	const xValues = [];
	const yValues = [];

	for (var x=0; x<jsonArray.length; x++){
		var item = jsonArray[x];
		var kg = item['WEIGHT'];
		var date = item['DATE'];
		xValues.push(x);
		yValues.push(kg);
	}
	var maxValue = Math.max(...yValues);
	maxValue = Math.ceil((maxValue+1)/10)*10;
	console.log("maxValue: " + maxValue);
	new Chart("myChart", {
	  type: "line",
	  data: { 
		labels: xValues,
		datasets: [{
		  fill: false,
		  lineTension: 0,
		  backgroundColor: "rgba(0,0,255,1.0)",
		  borderColor: "rgba(0,0,255,0.1)",
		  data: yValues
		}]
	  },
	  options: {
		legend: {display: false},
		title: {
		  display: true,
		  text: "My Weight",
		  fontSize: 16
		},
		scales: {
		  yAxes: [
				  {ticks: {min: 50, max:maxValue}}
				 ]
		  }
		}
	});
}

function loadDoc() {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
	console.log(this.responseText);
	plotData(this.responseText);
  }
  xhttp.open("GET", "get_data.php");
  xhttp.send();
}

loadDoc();

</script>
</html>
<?php
include "../database.php";




if (!empty($_POST["request"])) {
	$content = $_POST['request'];
	if (is_numeric($content)){
	  $pdo = new PDO("mysql:host=$host;dbname=$database_weight", $user,   $password);
	  $data = [
		'content' => $content
	  ];
	 $sql = "INSERT INTO $table_weight (WEIGHT) VALUES (:content)";
	  $stmt= $pdo->prepare($sql);
	  $stmt->execute($data);
	}
	header("Location: index.php");
    exit; 
}

try {
  $db = new PDO("mysql:host=localhost;dbname=$database_weight", $user, $password);
  echo "<h2>MY WEIGHT</h2>";
    echo "<table>";
  echo "<tr>";
  echo "<th>No.</th>";
  echo "<th>Weight</th>";
  echo "<th>Date</th>";
  echo "</tr>";
  $count = -1;
  foreach($db->query("SELECT * FROM $table_weight ORDER BY ID ASC") as $row) {
  $count = $count + 1;
  } 
  foreach($db->query("SELECT * FROM $table_weight ORDER BY ID DESC") as $row) {
  echo "<tr><td>" . $count . "</td><td>" .  $row['WEIGHT'] . " kg</td>";
  echo "<td> " . $row['DATE']. "</td></tr>";
  $count = $count - 1;
  } 
  echo "</table>";
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
