<?php
$servername = "localhost";
$username = "USERNAME";
$password = "PASSWORD";
$dbname = "earthquake";
$mod = $_GET["mod"];
$sql = "SELECT * FROM data ORDER BY DATE DESC, TIME DESC LIMIT 30";
$sql2 = "SELECT * FROM data ORDER BY DATE DESC, TIME DESC  LIMIT 1";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

		if($mod ==1){$getsql = $sql;}
		if($mod==2){$getsql=$sql2;}
		$result = mysqli_query($conn, $getsql);
		//$dataArr[] = [];
		while($data = mysqli_fetch_array($result)){
			if(!empty ($data)){
			$dataArr[] = $data;
			}
		}
	$js_data = json_encode($dataArr);
	
	echo $js_data;
		
	mysqli_close($conn);
		
?>
