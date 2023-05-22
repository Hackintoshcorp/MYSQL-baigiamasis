<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['id'];
$table = $_POST['table'];
$values = $_POST['values'];

$query = "UPDATE $table SET ";
foreach ($values as $key => $value) {
    $query .= "$key = '$value', ";
}
$query = rtrim($query, ', '); // Remove the trailing comma and space
$query .= " WHERE id = $id";

$result = $conn->query($query);
if ($result) {
    // Update successful
    echo "Record updated successfully.";
} else {
    // Handle the query error
    echo "Error updating record: " . $conn->error;
}
}
$conn->close();
?>