
<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database access configuration
$config["dbuser"] = "ora_kellyz02";			// change "cwl" to your own CWL
$config["dbpassword"] = "a46990602";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors
$constructor_names = NULL; 


$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// start PHP script
function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
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

function executeBoundSQL($cmdstr, $list) {
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

function connectToDB() {
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

function get_header_names($result) {
    $sql_h = "SELECT DISTINCT COLUMN_NAME
                    FROM ALL_TAB_COLUMNS
                    WHERE TABLE_NAME = UPPER('$result')";
    return executePlainSQL($sql_h);
}

// --- OLD VERSION ---
// function printResult($result, $tableName, $result_header) { //prints results from a select statement

//     // Output data of each row
//     global $db_conn;
//     echo "<table border='1'><tr>";
//     echo "<tr style='background-color: #f2f2f2; border-bottom: 1px solid #dddddd;'>";
//     if (connectToDB()) {
//         echo "<h1>". $tableName ."</h1>"; 
//         while ($name = OCI_Fetch_Array($result_header, OCI_ASSOC)) {
//             foreach ($name as $header) {
//                 echo "<th style='padding: 8px; text-align: left; border-right: 1px solid #dddddd;'>{$header}</th>";
//             }
//         }
//         while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
//             echo "</tr>";
//             foreach ($row as $value) {
//                 echo "<td style='padding: 8px; border-right: 1px solid #dddddd;'>{$value}</td>";
//             }
//         echo "</tr>";
//         } 
//     echo "</table>";
//     }

// }

// --- NEW VERSION --
function printResult($result, $tableName) {
    // Output data of each row
    global $db_conn;
    echo "<table border='1'><tr>";
    echo "<tr style='background-color: #f2f2f2; border-bottom: 1px solid #dddddd;'>";

    echo "<h1>" . $tableName . "</h1>";

    if ($result) {
        // Fetch result headers
        $numCols = oci_num_fields($result);
        echo "<table border='1'><tr style='background-color: #f2f2f2; border-bottom: 1px solid #dddddd;'>";

        for ($i = 1; $i <= $numCols; $i++) {
            $colName = oci_field_name($result, $i);
            echo "<th style='padding: 8px; text-align: left; border-right: 1px solid #dddddd;'>{$colName}</th>";
        }

        echo "</tr>";

        // Fetch and display rows
        while ($row = oci_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td style='padding: 8px; border-right: 1px solid #dddddd;'>{$value}</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No results found.";
    }
}

function printResultWithoutTable($result) { // formats SQL request to options 
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        foreach ($row as $value) {
            echo "<option value='$value'>". $value ."</option>"; 
        }
    }
}

// display query
function handleConstructorDisplayRequest($tableName) {

    global $db_conn;
    if (connectToDB()) {
        $result = executePlainSQL("SELECT * FROM $tableName");
        printResult($result, $tableName);
    }
}

// dropdown query
function handleConstructorDropdownRequest() {

    global $db_conn;
    global $constructor_names; 
    if (connectToDB()) {
        $constructor_names = executePlainSQL("SELECT DISTINCT ConstructorName FROM Constructor");
        printResultWithoutTable($constructor_names); 
    }
}

function handleDriverDisplayRequest($tableName) {

    global $db_conn;
    if (connectToDB()) {
        $sql = "SELECT DISTINCT t.*, d.driverNumber, d.numberOfWins, d.numberOfPodiums, d.numberOfPolePositions
                FROM Driver d, TeamMember t
                WHERE d.employeeId = t.employeeId";
        $result = executePlainSQL($sql);
        //$result_header = get_header_names($result);
        // $sql_h = "SELECT DISTINCT COLUMN_NAME
        //             FROM ALL_TAB_COLUMNS
        //             WHERE TABLE_NAME = UPPER('$result')";
        // $result_header = executePlainSQL($sql_h);
        printResult($result, $tableName);
    }
}

function handleDriverDropdownRequest() {

    global $db_conn;
    global $employee_ids; 
    if (connectToDB()) {
        $sql = "SELECT DISTINCT t.employeeId
                FROM Driver d, TeamMember t
                WHERE d.employeeId = t.employeeId";
        $employee_ids = executePlainSQL($sql);
        printResultWithoutTable($employee_ids); 
    }
}

function handleGrandPrixDisplayRequest($tableName) {
    global $db_conn;
    if (connectToDB()) {
        $sql = "SELECT DISTINCT *
                FROM GrandPrix_Ref gpref, GrandPrix_2 gp2, GrandPrix_3 gp3, GrandPrix_4 gp4, GrandPrix_5 gp5
                WHERE gpref.circuitName = gp2.circuitName AND
                    gpref.circuitName = gp3.circuitName AND
                    gp2.circuitName = gp4.circuitName AND
                    gp2.year = gp4.year AND
                    gp2.circuitName = gp5.circuitName AND
                    gp2.year = gp5.year";
        $result = executePlainSQL($sql);
        printResult($result, $tableName);
    }
}

function handleGrandPrixRequest() {

    // global $db_conn;
    // global ; 
    // if (connectToDB()) {
    //     $sql = ;
    //     $ = executePlainSQL($sql);
    //     printResultWithoutTable($); 
    // }
}

// SELECTION query

// PROJECTION query

// JOIN query 

// Aggregation with GROUP BY query

// Aggregation with HAVING query

// Nested Aggregation with GROUP BY query

// DIVISION query

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP

// front end code
include('main.html'); 

?>