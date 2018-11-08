<?php
$servername = "localhost";
$username = "USERNAME";
$password = "PASSWORD";
$dbname = "earthquake";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$freq = $_GET["freq"];
 
$sql = "INSERT INTO data (freq, date, time)VALUES ($freq, CURRENT_DATE, CURRENT_TIME)";
$sql2 = "DELETE FROM data WHERE date < CURRENT_DATE - INTERVAL 1 DAY";


if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
	mysqli_query($conn, "commit");

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

if ($conn->query($sql2) === TRUE) {
    echo "Well Deleted";
	mysqli_query($conn, "commit");

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>