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

$sql = "DELETE FROM data";

if ($conn->query($sql) === TRUE) {
    echo "잘가...";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>