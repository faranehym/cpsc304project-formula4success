

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

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
include('main.php'); 
include('grandprix.html'); 

function handleSelectionRequest() {
    global $db_conn;

    $input1 = $_GET["input1"];
    $input2 = $_GET["input2"];
    $filter_combo = $_GET["filterCombo"];

    if ($filter_combo!="1" && $filter_combo!="2" && $filter_combo!="3" && $filter_combo!="4") {
        echo "Error: Please select a filter combination or input valid filter values.";
    } else {
        if ($filter_combo=="1") {
            $sql = "SELECT DISTINCT gpref.circuitName, city, gp2.year, viewership, country, attendance
                    FROM GrandPrix_Ref gpref, GrandPrix_2 gp2, GrandPrix_3 gp3, GrandPrix_4 gp4, GrandPrix_5 gp5
                    WHERE gpref.circuitName = gp2.circuitName AND
                        gpref.circuitName = gp3.circuitName AND
                        gp2.circuitName = gp4.circuitName AND
                        gp2.year = gp4.year AND
                        gp2.circuitName = gp5.circuitName AND
                        gp2.year = gp5.year AND
                        gp5.year = '$input1' AND
                        gp5.gpName = '$input2'";
        } else if ($filter_combo=="2") {
            $sql = "SELECT DISTINCT gpref.circuitName, city, gp2.year, viewership, country, attendance
                    FROM GrandPrix_Ref gpref, GrandPrix_2 gp2, GrandPrix_3 gp3, GrandPrix_4 gp4, GrandPrix_5 gp5
                    WHERE gpref.circuitName = gp2.circuitName AND
                        gpref.circuitName = gp3.circuitName AND
                        gp2.circuitName = gp4.circuitName AND
                        gp2.year = gp4.year AND
                        gp2.circuitName = gp5.circuitName AND
                        gp2.year = gp5.year AND
                        gp5.year = '$input1' AND
                        gp3.country = '$input2'";
        } else if ($filter_combo=="3") {
            $sql = "SELECT DISTINCT gpref.circuitName, city, gp2.year, viewership, country, attendance
                    FROM GrandPrix_Ref gpref, GrandPrix_2 gp2, GrandPrix_3 gp3, GrandPrix_4 gp4, GrandPrix_5 gp5
                    WHERE gpref.circuitName = gp2.circuitName AND
                        gpref.circuitName = gp3.circuitName AND
                        gp2.circuitName = gp4.circuitName AND
                        gp2.year = gp4.year AND
                        gp2.circuitName = gp5.circuitName AND
                        gp2.year = gp5.year AND
                        gp5.year = '$input1' AND
                        gp5.circuitName = '$input2'";
        } else if ($filter_combo=="4") {
            $sql = "SELECT DISTINCT gpref.circuitName, city, gp2.year, viewership, country, attendance
                    FROM GrandPrix_5
                    JOIN GrandPrix_4 USING (year, circuitName)
                    JOIN GrandPrix_3 USING (circuitName)
                    JOIN GrandPrix_Ref USING (circuitName)
                    WHERE gpName = '$input1' OR circuitName = '$input2'";
        }
        $result = executePlainSQL($sql);
        oci_commit($db_conn);
        printResult($result, "Grand Prix");
    }
}

function handleNestedAggRequest() {
    global $db_conn;

    $sql = "SELECT c2.type, avg(gp4.attendance) AS AverageAttendance
            FROM Circuit_Ref cref, Circuit_2 c2, GrandPrix_Ref gpref, GrandPrix_2 gp2, GrandPrix_4 gp4
            WHERE cref.numberOfLaps = c2.numberOfLaps AND
                c2.circuitName = gpref.circuitName AND
                gpref.circuitName = gp2.circuitName AND
                gp2.circuitName = gp4.circuitName AND
                gp2.year = gp4.year AND cref.numberOfLaps IN (SELECT cref2.numberOfLaps
                                                            FROM Circuit_Ref cref2
                                                            WHERE cref2.length > 300)
            GROUP BY c2.type";
    
    $result = executePlainSQL($sql);
    oci_commit($db_conn);
    printResult($result, "Grand Prix");
}

function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('selectionQueryRequest', $_GET)) {
            handleSelectionRequest();
        } else if (array_key_exists('nestedAggRequest', $_GET)) {
            handleNestedAggRequest();
        }
    } 
}

if (isset($_GET['selectionSubmit'])) {
    handleGETRequest();
} else if (isset($_GET['nestedAggSubmit'])) {
    handleGETRequest();
}


?>

<html>
    <div class="accordion mt-3" id="accordionExample">
        
        <!-- SELECTION ACCORDION ITEM -->
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Filter Grand Prix (Selection)
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form method="GET" action="grandprix.php">
                    <input type="hidden" id="selectionQueryRequest" name="selectionQueryRequest">
                    <!-- Filtering combinations -->
                    <h6 class="mt-3">Select what combination of Grand Prix attributes to filter on:</h6>
                    <br>
                    <div class="row">
                        <div class="col">
                            <label for="inputState" class="form-label">Filter Combinations</label>
                            <select name="filterCombo" id="inputState" class="form-select">
                                <option selected>Filter on...</option>
                                <option value="1">Filter 1: Year AND Filter 2: Grand Prix Name</option>
                                <option value="2">Filter 1: Year AND Filter 2: Country</option>
                                <option value="3">Filter 1: Year AND Filter 2: Circuit Name</option>
                                <option value="4">Filter 1: Grand Prix Name OR Filter 2: Circuit Name</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <label for="input1" class="form-label">Filter 1</label>
                            <input type="text" class="form-control" name="input1" id="input1" placeholder="">
                        </div>
                        <div class="col">
                            <label for="input2" class="form-label">Filter 2</label>
                            <input type="text" class="form-control" name="input2" id="input2" placeholder="">
                        </div>
                    </div>


                    <div class="col-12 mt-3 mt-3">
                            <button type="submit" class="btn btn-primary" name="selectionSubmit" href="#collapseFilter">Filter</button> 
                    </div>
                    <div class="collapse" id="collapseFilter">
                        <div class="card card-body">
                            <?php
                                handleSelectionRequest();
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>


        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Circuit Type Spectatorship
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form method="GET" action="grandprix.php">
                    <input type="hidden" id="nestedAggRequest" name="nestedAggRequest">
                    <div class="row">
                        <div class="col">
                            <label for="inputState" class="form-label">For each circuit type, find the average number of people in attendance at Grand Prixs with that type of circuit. Only include Grand Prixs where the circuit length is greater than 300 km.</label>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary" name="nestedAggSubmit" href="#collapseNested" role="button" aria-expanded="false" aria-controls="collapseNested" data-bs-toggle="collapse">Search</button> 
                    </div>  
                </form>    
            </div>
        </div>
    

    <!-- currently only works if another submit request is executed -->
    <div class="d-flex justify-content-center">
        <?php
        
            handleGrandPrixDisplayRequest("Grand Prix");
        ?>
    </div>

    
</html>

