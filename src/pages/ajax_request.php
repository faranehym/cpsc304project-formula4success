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


// working, separate copy 
// function handleGetTableAttributesWorking() {
//     global $db_conn;
//     $table_name = $_GET['tableName']; 
//     global $sql_view, $sql_drop, $sql_columns; 
//     // $sql_view = $sql_drop = $sql_columns = ''; 

//     if ($table_name == "PARTNER") {
//         $sql_view = "CREATE VIEW COMPLETEPARTNER AS
//                      SELECT *
//                      FROM PARTNER_2 NATURAL JOIN PARTNER_REF"; 
//         $sql_drop = "DROP VIEW COMPLETEPARTNER";
//         $table_name = 'COMPLETEPARTNER'; 

//         $sql_columns = "SELECT DISTINCT COLUMN_NAME
//                         FROM ALL_TAB_COLUMNS
//                         WHERE TABLE_NAME = UPPER('$table_name')";
        
//         if (connectToDB()) {
//             executePlainSQL($sql_view); 
//             $attributes = executePlainSQL($sql_columns); 
//             executePlainSQL($sql_drop); 
//             oci_commit($db_conn); 
//             printAttributes($attributes);
//         }
//     }
// }

function handleGetTableAttributes() {
    global $db_conn;
    global $table_name; 
    $table_name = $_GET['tableName']; 
    global $sql_view, $sql_drop, $sql_columns; 
    // $sql_view = $sql_drop = $sql_columns = ''; 

    if ($table_name == "PARTNER") {
        $sql_view = "CREATE VIEW COMPLETEPARTNER AS
                     SELECT *
                     FROM PARTNER_2 NATURAL JOIN PARTNER_REF"; 
        $sql_drop = "DROP VIEW COMPLETEPARTNER";
        $table_name = 'COMPLETEPARTNER'; 
        
        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($table_name == "CIRCUIT") {
        $sql_view = "CREATE VIEW COMPLETECIRCUIT AS
                    SELECT *
                    FROM CIRCUIT_2 NATURAL JOIN CIRCUIT_REF"; 
        $sql_drop = "DROP VIEW COMPLETECIRCUIT";
        $table_name = 'COMPLETECIRCUIT'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($table_name == "GRANDPRIX") {
        $sql_view = "CREATE VIEW COMPLETEGRANDPRIX AS
                    SELECT *
                    FROM GRANDPRIX_REF NATURAL JOIN GRANDPRIX_2 NATURAL JOIN GRANDPRIX_3 NATURAL JOIN GRANDPRIX_4 NATURAL JOIN GRANDPRIX_5"; 
        $sql_drop = "DROP VIEW COMPLETEGRANDPRIX";
        $table_name = 'COMPLETEGRANDPRIX'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($table_name == "CONSTRUCTORSTANDING") {
        $sql_view = "CREATE VIEW COMPLETECONSTRUCTORSTANDING AS
                    SELECT *
                    FROM GRANDPRIX_CONSTRUCTORSTANDING_REF NATURAL JOIN GRANDPRIX_CONSTRUCTORSTANDING_2"; 
        $sql_drop = "DROP VIEW COMPLETECONSTRUCTORSTANDING";
        $table_name = 'COMPLETECONSTRUCTORSTANDING'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($table_name == "DRIVERSTANDING") {
        $sql_view = "CREATE VIEW COMPLETEDRIVERSTANDING AS
                    SELECT *
                    FROM GRANDPRIX_DRIVERSTANDING_REF NATURAL JOIN GRANDPRIX_DRIVERSTANDING_2"; 
        $sql_drop = "DROP VIEW COMPLETEDRIVERSTANDING";
        $table_name = 'COMPLETEDRIVERSTANDING'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } 

    $sql_columns = "SELECT DISTINCT COLUMN_NAME
                    FROM ALL_TAB_COLUMNS
                    WHERE TABLE_NAME = UPPER('$table_name')";

    if (connectToDB()) {
        $attributes = executePlainSQL($sql_columns);
        if (isset($sql_drop)) {
            executePlainSQL($sql_drop); 
        }
        oci_commit($db_conn);
        printAttributes($attributes);
    }
}

function printAttributes($result) { // formats SQL request to options 
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        foreach ($row as $value) {
            echo "<div class=\"form-check\">
                    <input class=\"form-check-input\" name=\"attributeCheckBoxes[]\" type=\"checkbox\" value=\"". $value ."\" id=\"flexCheckDefault\">
                    <label class=\"form-check-label\" for=\"flexCheckDefault\">
                        ". $value ."
                    </label>
                </div>"; 
        }
    }
}


if (isset($_GET['action'])) {
    $action = $_GET['action']; 
    if ($action == "getTableAttributes") {
        handleGetTableAttributes(); 
    }
}

?>
