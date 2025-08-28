<?php
	// Add CORS headers to allow cross-origin requests
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type");
	
	// Handle preflight OPTIONS request
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		http_response_code(200);
		exit();
	}

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError($conn->connect_error);
	} 
	else
	{
		$stmt = $conn->prepare("SELECT ID, Login, Password, firstName, lastName FROM Users");
		$stmt->execute();
		$result = $stmt->get_result();
		
		$users = array();
		while($row = $result->fetch_assoc())
		{
			$users[] = array(
				"id" => $row["ID"],
				"login" => $row["Login"],
				"password" => $row["Password"],
				"firstName" => $row["firstName"],
				"lastName" => $row["lastName"]
			);
		}
		
		$stmt->close();
		$conn->close();
		
		returnWithInfo($users);
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson($obj)
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError($err)
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
	}
	
	function returnWithInfo($users)
	{
		$retValue = '{"users":' . json_encode($users) . ',"error":""}';
		sendResultInfoAsJson($retValue);
	}
?>
