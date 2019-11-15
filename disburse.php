<?php
start:
include 'connection.php';
//masukkan pilihan fitur
	echo "\nPilih nomor fitur kemudian tekan Enter";
	echo "\n1. Input Information";
	echo "\n2. Update Information\n";
	$pilihan = fopen ("php://stdin","r");
	$pilihan = fgets($pilihan);
//input data	
	if ($pilihan==1){
		echo "\n\nAnda memilih fitur Input Information";
		echo "\nLengkapi data berikut :";
			echo "\nMasukkan Kode Bank :";
			$BankCode = fopen ("php://stdin","r");
			$BankCode = fgets($BankCode);
			echo "\nMasukkan Account Number :";
			$AccNum = fopen ("php://stdin","r");
			$AccNum = fgets($AccNum);
			echo "\nMasukkan Nominal Pembayaran :";
			$Amount = fopen ("php://stdin","r");
			$Amount = fgets($Amount);
			echo "\nMasukkan Remark :";
			$Remark = fopen ("php://stdin","r");
			$Remark = fgets($Remark);
//post ke Flip		
		
		$username = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";
		$password = "";
		$remote_url = 'https://nextar.flip.id/disburse';
		$datamasukan = array('bank_code' => $BankCode, 'account_number' => $AccNum,
								'amount' => $Amount, 'remark' => $Remark);
		
		$options = array(
		'http'=>array(
				'method'=> "POST",
				'header'=> "Content-type: application/x-www-form-urlencoded\r\n".
							"Authorization: Basic " . base64_encode("$username:$password"),
				'content'=> http_build_query($datamasukan)                
		)
		);
		$context = stream_context_create($options);
		$jsonResponse = file_get_contents($remote_url, false, $context);
		$jsonArray = json_decode($jsonResponse, true);
		$ID = $jsonArray['id'];
		$amount = $jsonArray['amount'];
		$status = $jsonArray['status'];
		$timestamp = $jsonArray['timestamp'];
		$bank_code = $jsonArray['bank_code'];
		$account_number = $jsonArray['account_number'];
		$benificiary_name = $jsonArray['beneficiary_name'];
		$remark = $jsonArray['remark'];
		$receipt = $jsonArray['receipt'] ;
		$time_served = $jsonArray['time_served'];
		$fee = $jsonArray['fee'];
		
//insert ke database		
		$sql="	INSERT INTO tablearief (id,amount,status_disburse,timestamp_disburse,bank_code,account_number,beneficiary_name,remark,receipt,time_served,fee) 
				VALUES ( '$ID', '$amount', '$status', '$timestamp','$bank_code', '$account_number', '$benificiary_name', '$remark','$receipt', '$time_served', '$fee')";
		if ($conn->query($sql) === TRUE) {
			echo "\nDisbursement data sent, ID: ";
			echo "$ID\n";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$conn->close();	
		goto start;
	}		
	else if ($pilihan == 2){
//fitur update		
		echo"Anda memilih fitur update Information";
		echo"\nMasukkan ID : ";
		$idupdate = fopen ("php://stdin","r");
		$idupdate = fgets($idupdate);
//get dari Flip		
		$username = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";
		$password = "";
		$remote_url = 'https://nextar.flip.id/disburse/'.$idupdate;
		$options = array(
		'http'=>array(
				'method'=>"GET",
				'header' => "Authorization: Basic " . base64_encode("$username:$password")                 
		)
		);
		$context = stream_context_create($options);
		$jsonResponse = file_get_contents($remote_url, false, $context);
		$jsonArray = json_decode($jsonResponse, true);
		$ID=$jsonArray['id'];
		$status = $jsonArray['status'];
		$receipt = $jsonArray['receipt'] ;
		$time_served = $jsonArray['time_served'];
		
//update ke database		
		$sql="	UPDATE tablearief	
				SET status_disburse = '$status', receipt = '$receipt', time_served = '$time_served'
				WHERE id = '$ID'";
		if (mysqli_query($conn, $sql)) {
			echo "\nDisbursement status: ";
			echo "$status\n";
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}
		mysqli_close($conn);
		goto start;
		}
	else{
		echo"Maukkan nomor dengan benar\n";
		goto start;
	}		
	
	?>
