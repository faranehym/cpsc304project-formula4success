<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database access configuration
$config["dbuser"] = "ora_faranehm";			// change "cwl" to your own CWL
$config["dbpassword"] = "a60431905";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>

<head>
	<title>Formula 4 Success - Your hub for all things F1</title>
</head>

<body>

<?php

function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function executePlainSQL($cmdstr)
	{ //takes a plain (no bound variables) SQL command and executes it
		//echo "<br>running ".$cmdstr."<br>";
		global $db_conn, $success;

		$statement = oci_parse($db_conn, $cmdstr);
		//There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
			echo htmlentities($e['message']);
			$success = False;
		}

		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = oci_error($statement); // For oci_execute errors pass the statementhandle
			echo htmlentities($e['message']);
			$success = False;
		}

		return $statement;
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				//echo $val;
				//echo "<br>".$bind."<br>";
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}
	}

	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}

	function printResult($result, $tableName) { //prints results from a select statement

		// Output data of each row
		echo "<table border='1'><tr>";
		// Output column headers
		// while ($fieldinfo = OCI_Fetch_Array($result, OCI_ASSOC)) {
		// 	echo "<th>" . $fieldinfo->name . "</th>";
		// }
		// echo "</tr>";

		// Output data of each row
		while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
			echo "<tr>";
			foreach ($row as $value) {
				echo "<td>" . $value . "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		
	}

	// display query
	function handleDisplayRequest($tableName) {

		global $db_conn;
		if (connectToDB()) {
			$result = executePlainSQL("SELECT * FROM $tableName");
	 		printResult($result, $tableName);
		}
	}

	// INSERT query 
	function handleInsertRequest($data, $tableName) {

		global $db_conn;

		# get values from user and insert into requested table
		$columns = implode(", ", array_keys($data));
		$values = "'" . implode("', '", array_values($data)) . "'";
		$sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

		executePlainSQL($sql);
		oci_commit($db_conn);
	}

	// DELETE query

	// UPDATE query

	// SELECTION query

	// PROJECTION query

	// JOIN query 

	// Aggregation with GROUP BY query

	// Aggregation with HAVING query

	// Nested Aggregation with GROUP BY query

	// DIVISION query

	handleDisplayRequest("Car");
	handleDisplayRequest("TeamMember");
	handleInsertRequest(array(
		"model" => "RB18",
		"engine" => "Red Bull Powertrains - Honda",
		"constructorName" => "Red Bull Racing"
	), "Car");
	handleDisplayRequest("Car");
?>

<body>


<html>