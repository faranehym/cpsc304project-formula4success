<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// SOURCE: from CPSC 304 23W Tutorial 6 Starter Code
// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// SOURCE: from CPSC 304 23W Tutorial 6 Starter Code
// Database access configuration
// $config["dbuser"] = "ora_kellyz02";			// change "cwl" to your own CWL
// $config["dbpassword"] = "a46990602";	// change to 'a' + your student number
$config["dbuser"] = "ora_faranehm";			// change "cwl" to your own CWL
$config["dbpassword"] = "a60431905";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

include('main.php'); 
include('constructor.html'); 

function handleUpdateRequest() {
    global $db_conn;  
    if (!isset($_POST['constructorName'])) {
        echo "<div class=\"container-fluid alert alert-danger mt-3\" role=\"alert\">
                Error: Constructor value cannot be empty!
            </div>"; 
    } else if (!isset($_POST['newPoints']) || $_POST['newPoints'] === null) {
        echo "<div class=\"container-fluid alert alert-danger mt-3\" role=\"alert\">
                Error: Points value cannot be empty!
            </div>"; 
    } else {
        $constructor_name = $_POST['constructorName'];
        $new_points = $_POST['newPoints'];
        if ($new_points === '') {
            echo "<div class=\"container-fluid alert alert-danger mt-3\" role=\"alert\">
                    Error: Points value cannot be empty!
                </div>"; 
        } else {
            executePlainSQL("UPDATE Constructor SET  numberOfWins='" . $new_points . "' WHERE constructorName='" . $constructor_name . "'");
            oci_commit($db_conn);
            echo "<div class=\"container-fluid alert alert-success mt-3\" role=\"alert\">
                    Success: Constructor wins updated!
                </div>";
        }
    }
}

function handleAverageRequest() {
    global $db_conn;

    $sql = "SELECT c.nationality AS country, AVG(s.sponsorshipAmount) AS AverageDollarAmount
            FROM Sponsors s, Constructor c
            WHERE s.constructorName = c.constructorName
            GROUP BY c.nationality";
    $result = executePlainSQL($sql);
    oci_commit($db_conn);
    printResult($result, "Constructor");
}

function handleEngineRequest() {
    global $db_conn;

    $sql = "CREATE VIEW constructorCarInfo(constructorName, nationality, numberOfWins, model, engine) AS
            SELECT c.constructorName, nationality, numberOfWins, model, engine
            FROM Constructor c
            LEFT OUTER JOIN Car c2 ON c.constructorName = c2.constructorName";
            
    $sql2 = "select engine, SUM(numberOfWins)
            from constructorCarInfo
            group by engine
            having SUM(numberOfWins) > 5";
    
    $sql3 = "DROP VIEW constructorCarInfo";
    $result1 = executePlainSQL($sql);
    $result = executePlainSQL($sql2);
    executePlainSQL($sql3);
    oci_commit($db_conn);
    printResult($result, "Constructor");
}

function handleDriverRequest() {
    global $db_conn;

    $sql = "CREATE VIEW DrivesCar(constructorName, model, employeeId) AS
            SELECT cr.constructorName, cr.model, d.employeeId
            from Drives d, Car cr
            where cr.model = d.model";

    $sql2 = "SELECT DISTINCT c.constructorName
            FROM Constructor c
            WHERE EXISTS (
                SELECT 1
                FROM Driver d, DrivesCar dc
                WHERE d.employeeId = dc.employeeId
                AND c.constructorName = dc.constructorName
                AND d.numberOfWins > 0
            )";

    $sql3 = "DROP VIEW DrivesCar";
    $result1 = executePlainSQL($sql);
    $result = executePlainSQL($sql2);
    executePlainSQL($sql3);
    oci_commit($db_conn);
    printResult($result, "Constructor");
}

function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('updateQueryRequest', $_POST)) {
            handleUpdateRequest();
        } else if (array_key_exists('averageQueryRequest', $_POST)) {
            handleAverageRequest();
        }
    } 
}

function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('averageQueryRequest', $_GET)) {
            handleAverageRequest();
        } else if (array_key_exists('sumQueryRequest', $_GET)) {
            handleEngineRequest();
        } else if (array_key_exists('driverQueryRequest', $_GET)) {
            handleDriverRequest();
        }
    } 
}

if (isset($_POST['updateSubmit'])) {
    handlePOSTRequest();
} else if (isset($_GET['averageSubmit'])) {
    handleGETRequest();
} else if (isset($_GET['sumSubmit'])) {
    handleGETRequest();
}   else if (isset($_GET['driverSubmit'])) {
    handleGETRequest();
}
?>

<html>
    <div class="accordion mt-3" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Update Constructor Points
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form method="POST" action="constructor.php">
                        <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Choose the Constructor</label>
                                <select name="constructorName" id="inputState" class="form-select">
                                    <option value="" disabled selected>Constructor name...</option>
                                        <?php
                                            handleConstructorDropdownRequest(); 
                                        ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="inputState" class="form-label">Updated amount of points</label>
                                <input name="newPoints" type="number" class="form-control" placeholder="Points value" aria-label="">
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-secondary" name="updateSubmit">Update</button> 
                        </div> 
                    </form> 
                </div>
            </div>
        </div>

        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Constructors' Sponsors
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form method="GET" action="constructor.php">
                        <input type="hidden" id="averageQueryRequest" name="averageQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Find the average sponsorship amount given to the constructor's country</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" class="btn btn-secondary" name="averageSubmit" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" data-bs-toggle="collapse">Search</button> 
                        </div> 
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <?php
                                    handleAverageRequest();
                                ?>
                            </div>
                        </div>
                    </form>                   
                </div>
            </div>
        </div>
        
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Engine Manufacturers
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form method="GET" action="constructor.php">
                        <input type="hidden" id="sumQueryRequest" name="sumQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Find the engine manufacturers with more than 5 championship wins.</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" class="btn btn-secondary" name="sumSubmit" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" data-bs-toggle="collapse">Search</button> 
                        </div> 
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <?php
                                    handleEngineRequest();
                                ?>
                            </div>
                        </div>
                    </form>                   
                </div>
            </div>
        </div>
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
            Constructors' Race Wins
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form method="GET" action="constructor.php">
                        <input type="hidden" id="driverQueryRequest" name="driverQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Find all constructors with drivers winning at least one race</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" class="btn btn-secondary" name="driverSubmit" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" data-bs-toggle="collapse">Search</button> 
                        </div> 
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <?php
                                    handleDriverRequest();
                                ?>
                            </div>
                        </div>
                    </form>                   
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <?php
            handleConstructorDisplayRequest("Constructor");
        ?>
    </div>

</html>

