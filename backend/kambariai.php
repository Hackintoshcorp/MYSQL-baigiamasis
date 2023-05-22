<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$kambario_nr = $_POST['kambario_nr'];
$lovu_sk = $_POST['lovu_sk'];
$klase = $_POST['klase'];
$paros_kaina = $_POST['paros_kaina'];
$sql = "INSERT INTO kambariai (kambario_nr, lovu_sk, klase, paros_kaina) VALUES ('$kambario_nr', '$lovu_sk', '$klase', '$paros_kaina')";
 if ($conn->query($sql) === true) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
	
	}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$query = "SELECT * FROM kambariai";
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
			  <td id='a1' class='whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900'>" . $row['kambario_nr'] . "</td>
			  <td id='a3' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['lovu_sk'] . "</td>
			  <td id='a4' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['klase'] . "</td>
			  <td id='a5' class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['paros_kaina'] . "</td>
			  <td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
				<button id='editme' data-modal='kambariai_edit' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Redaguoti</button>
			  </td>
			  <td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
				<button id='deleteme' data-url='backend/kambariai.php' data-table='#content' data-category='kambariai' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Trinti</button>
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