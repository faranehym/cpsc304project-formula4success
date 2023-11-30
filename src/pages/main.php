
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
    global $db_conn, $success;

    $statement = oci_parse($db_conn, $cmdstr);

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

function connectToDB() {
    global $db_conn;
    global $config;

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

function printResult($result, $tableName) {
    // Output data of each row
    global $db_conn;

    if ($result) {
        // Fetch result headers
        $numCols = oci_num_fields($result);
        echo "<br>";
        echo "<div class=\"container-fluid mt-3\">"; 
        echo "<h4 class=\"table-title\"> Table: ". $tableName ."</h4>"; 
        // echo "<table class=\"table\" border='1'><thead style='background-color: #f2f2f2; border-bottom: 1px solid #dddddd;'>";
        // echo "<thead class=\"table-light\"><th>" . $tableName . "</th><tr style='background-color: #f2f2f2; border-bottom: 8px solid #dddddd;'>";
        echo "<div class=\"table-responsive\">"; 
        echo "<table class=\"table table-sm table-bordered table-hover\"><thead class=\"table-light\">";

        
        for ($i = 1; $i <= $numCols; $i++) {
            $colName = oci_field_name($result, $i);
            echo "<th class=\"table-headings\">{$colName}</th>";
        }

        echo "</thead>";
        echo "<tbody class=\"table-group-divider\">"; 

        // Fetch and display rows
        while ($row = oci_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td class=\"table-tuples\">{$value}</td>";
            }
            echo "</tr>";
        }

        echo "</tbody>";

        echo "</table>";
        echo "</div>"; 
        echo "</div>"; 
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
        $sql = "SELECT DISTINCT gpref.circuitName, city, gp2.year, viewership, country, attendance
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

function handleResetRequest() {
    global $db_conn;
    if (connectToDB()) {
        $sqlFile = 'formula4success.sql';

        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);

            if ($sql !== false) {
                // Split the SQL file into individual statements
                $sqlStatements = explode(';', $sql);

                // Execute each SQL statement
                foreach ($sqlStatements as $sqlStatement) {
                    if (!empty(trim($sqlStatement))) {
                        executePlainSQL($sqlStatement);
                    }
                }
                echo 'Tables reset successfully.';
            } else {
                echo 'Error reading SQL file.';
            }
        } else {
            echo 'SQL file not found.';
        }
    }
}

if (isset($_POST['resetSubmit'])) {
    handleResetRequest();
}
// MIGHT NOT NEED THIS
if (!isset($_SESSION['resetExecuted'])) {
    // Execute the function
    handleResetRequest();

    // Set the flag to indicate that the function has been executed
    $_SESSION['resetExecuted'] = true;
}

// front end code
include('main.html'); 

?>

<html>
    <nav class="navbar navbar-expand-lg" style="background-color: #ff1801;">
        <div class="container-fluid">
            <a class="navbar-title" href="home.php">FORMULA 4 SUCCESS
            <h6 class="navbar-subtitle">A Formula 1 Database</h6>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" href="constructor.php">Constructors</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="driver.php">Drivers</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="grandprix.php">Grand Prix</a>
                </li>
            </ul>
            <form class="d-flex" method="POST">
                <button class="btn btn-outline-success btn-reset" type="submit" name="resetSubmit">Reset Tables</button>
            </form>
            </div>
        </div>
    </nav>
    



</html>