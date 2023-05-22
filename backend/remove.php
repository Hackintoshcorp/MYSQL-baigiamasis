<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$table = $_POST['category'];
	$id = $_POST['id'];
	
	$sql = "DELETE FROM $table WHERE id = '$id'";

	if ($conn->query($sql) === true) {
        echo "Data deleted successfully";
		} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
}

$conn->close();
?>