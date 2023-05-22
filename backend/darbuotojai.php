<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$v_p = $_POST['vardas_pavarde'];
$email = $_POST['email'];
$tel_nr = $_POST['tel_nr'];
$laikas = $_POST['laikas'];
$pareigos = $_POST['pareigos'];

$sql = "INSERT INTO darbuotojai (vardas_pavarde, el_pastas, tel_nr, darbo_laikas, pareigos) VALUES ('$v_p', '$email', '$tel_nr', '$laikas', '$pareigos')";

if ($conn->query($sql) === true) {
        echo "Data inserted successfully";
} else {
        echo "Error: " . $sql . "<br>" . $conn->error;
}

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	

$query = "SELECT * FROM darbuotojai";
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
			  <td id='a1' class='whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900'>" . $row['vardas_pavarde'] . "</td>
			  <td id='a2' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['el_pastas'] . "</td>
			  <td id='a3' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['tel_nr'] . "</td>
			  <td id='a4' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['darbo_laikas'] . "</td>
			  <td id='a5' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['pareigos'] . "</td>
			  <td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
				<button id='editme' data-modal='darbuotojai_edit' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Redaguoti</button>
			  </td>
			  <td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
				<button id='deleteme' data-url='backend/darbuotojai.php' data-table='#content' data-category='darbuotojai' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Trinti</button>
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
}
$conn->close();
?>