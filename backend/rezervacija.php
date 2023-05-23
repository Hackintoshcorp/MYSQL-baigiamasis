<?php
require_once 'connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
$client = $_POST['client_data'];//t/n
$v_p = $_POST['vardas_pavarde'];
$tel = $_POST['tel_nr'];
$email = $_POST['el_pastas'];
$country = $_POST['salis'];
$city = $_POST['miestas'];
$street = $_POST['adresas'];
$room = $_POST['kambario_nr'];//liuksas
$worker = $_POST['aptarnaves_darbuotojas'];
$check_in = $_POST['atvyks'];
$check_out = $_POST['isvyks'];
$money = $_POST['suma'];
$spa = $_POST['spa'];//t/n
$auto = $_POST['auto'];//t/n
$food = $_POST['maitinimas'];//t/n
$date = date("d/m/Y");

$filtered_money = filter_var($money, FILTER_SANITIZE_NUMBER_INT);

if($client != "new"){
	$sql = "INSERT INTO rezervavimas (kliento_id, kambario_id, darbuotojo_id, atvyks, isvyks, maitinimas, auto_vieta_id, spa_laikas_id) VALUES ('$client', '$room', '$worker', '$check_in', '$check_out'" . (!empty($food) ? ", '$food'" : ", NULL") . (!empty($auto) ? ", '$auto'" : ", NULL") . (!empty($spa) ? ", '$spa'" : ", NULL") . ")";

	if ($conn->query($sql) === true) {
		$recently_inserted_id = $conn->insert_id;
			echo "Data inserted successfully";
	} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	
	$sql = "INSERT INTO mokejimas (rezervavimo_id, tipas, apmokejimo_laikas, mokama_suma) VALUES ($recently_inserted_id,'kortele',$date,$filtered_money)";

	if ($conn->query($sql) === true) {
		
			echo "Data inserted successfully";
	} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	if(!empty($spa)){
	$query = "UPDATE spa_vietos SET vietu_skaicius = vietu_skaicius - 1 WHERE id = $spa";

	if ($conn->query($query) === TRUE) {
		echo "Value updated successfully.";
	} else {
		echo "Error updating value: " . $conn->error;
	}
	}
	
	}else{
		
	$sql = "INSERT INTO klientai (vardas_pavarde, el_pastas, tel_nr, salis, miestas, adresas) VALUES ('$v_p', '$email', '$tel', '$country', '$city', '$street')";

	if ($conn->query($sql) === true) {
		$recently_inserted_id = $conn->insert_id;
			echo "Data inserted successfully";
	} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	$sql = "INSERT INTO mokejimas (rezervavimo_id, tipas, apmokejimo_laikas, mokama_suma) VALUES ($recently_inserted_id,'kortele',$date,$filtered_money)";
	
	if ($conn->query($sql) === true) {
		
			echo "Data inserted successfully";
	} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	$query = "SELECT id FROM klientai WHERE vardas_pavarde = '$v_p'";
	$result = $conn->query($query);

	if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
    } else {
        echo "No matching records found.";
    }
	} else {
		echo "Error executing the query: " . mysqli_error($conn);
	}
	$sql = "INSERT INTO rezervavimas (kliento_id, kambario_id, darbuotojo_id, atvyks, isvyks, maitinimas, auto_vieta_id, spa_laikas_id) VALUES ('$id', '$room', '$worker', '$check_in', '$check_out'" . (!empty($food) ? ", '$food'" : ", NULL") . (!empty($auto) ? ", '$auto'" : ", NULL") . (!empty($spa) ? ", '$spa'" : ", NULL") . ")";

	if ($conn->query($sql) === true) {
			echo "Data inserted successfully";
	} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	if(!empty($spa)){
	$query = "UPDATE spa_vietos SET vietu_skaicius = vietu_skaicius - 1 WHERE id = $spa";

	if ($conn->query($query) === TRUE) {
		echo "Value updated successfully.";
	} else {
		echo "Error updating value: " . $conn->error;
	}
	}
}

}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$data = $_GET['data'];
	if (is_numeric($data)) {
    switch ($data) {
        case "1":
        case "2":
        case "3":
        case "4":
        case "5":
            $table = ($data == "1") ? "klientai" :
                     (($data == "2") ? "kambariai" :
                     (($data == "3") ? "darbuotojai" :
                     (($data == "4") ? "spa_vietos" :
                     (($data == "5") ? "auto_vietos" : ""))));
            if ($table != "") {
                if ($table == "kambariai") {
                    $query = "SELECT * FROM $table k
                              WHERE NOT EXISTS (SELECT 1 FROM rezervavimas r WHERE r.kambario_id = k.id)";
                } elseif ($table == "auto_vietos") {
                    $query = "SELECT * FROM $table av
                              WHERE NOT EXISTS (SELECT 1 FROM rezervavimas r WHERE r.auto_vieta_id = av.id)"; 
				} elseif ($table == "spa_vietos") {
                    $query = "SELECT * FROM $table WHERE vietu_skaicius != 0";
                } else {
                    $query = "SELECT * FROM $table";
                }
                $result = $conn->query($query);

                if ($result) {
                    if ($result->num_rows > 0) {
                        $data = array();
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                        $json = json_encode($data);
                        echo $json;
                    } else {
                        echo "No data found.";
                    }
                } else {
                    echo "Error executing the query: " . mysqli_error($connection);
                }
            }
            break;
        case "6":
            $query = "SELECT r.*, k.vardas_pavarde, ka.kambario_nr, d.vardas_pavarde, av.auto_vieta, sv.laikas
          FROM rezervavimas r
          LEFT JOIN klientai k ON r.kliento_id = k.id
          LEFT JOIN kambariai ka ON r.kambario_id = ka.id
          LEFT JOIN darbuotojai d ON r.darbuotojo_id = d.id
          LEFT JOIN auto_vietos av ON r.auto_vieta_id = av.id
          LEFT JOIN spa_vietos sv ON r.spa_laikas_id = sv.id";

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
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['vardas_pavarde'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['kambario_nr'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['vardas_pavarde'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['atvyks'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['isvyks'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['maitinimas'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['auto_vieta'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-sm text-gray-500'>" . $row['laikas'] . "</td>
								<td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
									<button id='editme' data-id='" . $row['id'] . "' data-modal='rezervacija_edit' class='text-orange-600 hover:text-orange-900'>Redaguoti</button>
								</td>
								<td class='whitespace-nowrap px-6 py-4 text-right text-sm font-medium'>
									<button id='deleteme' data-url='backend/rezervacija.php?data=6' data-table='#content' data-category='rezervavimas' data-id='" . $row['id'] . "' class='text-orange-600 hover:text-orange-900'>Trinti</button>
								</td>
							</tr>
						";
					}
				} else {
					echo "No data found.";
				}
			} else {
				echo "Error executing the query: " . mysqli_error($conn);
			}

            break;
        default:
            break;
    }
}


}
$conn->close();
?>
