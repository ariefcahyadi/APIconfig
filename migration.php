 <?php
$servername = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS `dbarief`"); 
$conn->select_db('dbarief');
$sql="CREATE TABLE tablearief (
id VARCHAR(10) PRIMARY KEY,
    amount INT UNSIGNED NOT NULL, 
    status_disburse VARCHAR(30) NOT NULL,
    timestamp_disburse DATETIME,
    bank_code VARCHAR(30) NOT NULL,
    account_number VARCHAR(30) NOT NULL,
    beneficiary_name VARCHAR(30),
    remark VARCHAR(50),
    receipt VARCHAR(150),
    time_served VARCHAR(30),
    fee INT UNSIGNED
)";
if ($conn->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();
?> 
