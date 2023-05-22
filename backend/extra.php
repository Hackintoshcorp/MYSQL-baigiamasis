<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$form = $_POST['form'];

switch ($form) {
  case "auto":
  $auto_vieta = $_POST['auto_vieta'];
  $sql = "INSERT INTO auto_vietos (auto_vieta) VALUES ('$auto_vieta')";
	if ($conn->query($sql) === true) {
        echo "Data inserted successfully";
	} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
	
	

  break;
  case "spa":
  $vietos_spa = $_POST['vietos_spa'];
  $laikas_spa = $_POST['laikas_spa'];
  $sql = "INSERT INTO spa_vietos (laikas, vietu_skaicius) VALUES ('$laikas_spa', '$vietos_spa')";
	if ($conn->query($sql) === true) {
        echo "Data inserted successfully";
	} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
	
	
  break;
  default:
   echo "Unknown value.";
  break;
}
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$data = $_GET['data'];

if (is_numeric($data)) {
	
switch ($data) {
  case "1":
		$query = "SELECT * FROM auto_vietos";
		$result = $conn->query($query);

		// Check if the query executed successfully
		if ($result) {
			// Check if there are rows returned
			if ($result->num_rows > 0) {
				// Loop through each row and print the data
				while ($row = $result->fetch_assoc()) {
					// Print the data
					echo "
					<tr>
					  <td id='auto_vieta_value' class='whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900'>" . $row['auto_vieta'] . "</td>
					  <td class='whitespace-nowrap px-3 py-4 text-right text-sm font-medium'>
						<button id='editme' data-modal='auto_edit' data-id='" . $row['id'] . "' class='text-orange-600 mr-4 hover:text-orange-900'>Redaguoti</button>
						<button id='deleteme' data-url='backend/extra.php?data=1' data-table='#content_auto' data-category='auto_vietos' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Trinti</button>
					  </td>
					</tr>
					";
				   
				}
			} else {
				echo "No data found.";
			}
		} else {
			echo "Error executing the query: " . mysqli_error($connection);
		}
	break;
  case "2":
		$query = "SELECT * FROM spa_vietos";
		$result = $conn->query($query);

		// Check if the query executed successfully
		if ($result) {
			// Check if there are rows returned
			if ($result->num_rows > 0) {
				// Loop through each row and print the data
				while ($row = $result->fetch_assoc()) {
					// Print the data
					echo "
					<tr>
					  <td id='spa_laikas_value' class='whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900'>" . $row['laikas'] . "</td>
					  <td id='spa_vietu_skaicius_value' class='whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900'>" . $row['vietu_skaicius'] . "</td>
					  <td class='whitespace-nowrap px-3 py-4 text-right text-sm font-medium'>
							<button id='editme_2' data-modal='spa_edit' data-id='" . $row['id'] . "' class='text-orange-600 mr-4 hover:text-orange-900'>Redaguoti</button>
						<button id='deleteme' data-url='backend/extra.php?data=2' data-table='#content_spa' data-category='spa_vietos' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Trinti</button>
					  </td>
					</tr>
					";
				   
				}
			} else {
				echo "No data found.";
			}
		} else {
			echo "Error executing the query: " . mysqli_error($connection);
		}
	break;
  default:
    echo "Unknown value.";
    break;
}
}else{
	echo "Unknown value.";
}
}
$conn->close();
?>