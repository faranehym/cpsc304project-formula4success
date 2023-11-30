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
include('main.php');
include('home.html'); 

function handleProjectionRequest() {
    global $db_conn; 
    global $selected_table; 
    $selected_table = isset($_GET['selectedTable']) ? $_GET['selectedTable'] : '';
    $checked_attributes = $_GET['attributeCheckBoxes']; 
    global $sql_view, $sql_drop, $sql_columns; 
    
    if (isset($_GET['selectedTable'])) {
        $selected_table = $_GET['selectedTable']; 
    }

    if (empty($checked_attributes)) {
        echo "not working";
    }
    ////////////////////

    if ($selected_table == "PARTNER") {
        $sql_view = "CREATE VIEW PARTNER AS
                     SELECT *
                     FROM PARTNER_2 NATURAL JOIN PARTNER_REF"; 
        $sql_drop = "DROP VIEW PARTNER";
        $selected_table = 'PARTNER'; 
        
        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($selected_table == "CIRCUIT") {
        $sql_view = "CREATE VIEW CIRCUIT AS
                    SELECT *
                    FROM CIRCUIT_2 NATURAL JOIN CIRCUIT_REF"; 
        $sql_drop = "DROP VIEW CIRCUIT";
        $selected_table = 'CIRCUIT'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($selected_table == "GRANDPRIX") {
        $sql_view = "CREATE VIEW GRANDPRIX AS
                    SELECT *
                    FROM GRANDPRIX_REF NATURAL JOIN GRANDPRIX_2 NATURAL JOIN GRANDPRIX_3 NATURAL JOIN GRANDPRIX_4 NATURAL JOIN GRANDPRIX_5"; 
        $sql_drop = "DROP VIEW GRANDPRIX";
        $selected_table = 'GRANDPRIX'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($selected_table == "CONSTRUCTORSTANDING") {
        $sql_view = "CREATE VIEW CONSTRUCTORSTANDING AS
                    SELECT *
                    FROM GRANDPRIX_CONSTRUCTORSTANDING_REF NATURAL JOIN GRANDPRIX_CONSTRUCTORSTANDING_2"; 
        $sql_drop = "DROP VIEW CONSTRUCTORSTANDING";
        $selected_table = 'CONSTRUCTORSTANDING'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } else if ($selected_table == "DRIVERSTANDING") {
        $sql_view = "CREATE VIEW DRIVERSTANDING AS
                    SELECT *
                    FROM GRANDPRIX_DRIVERSTANDING_REF NATURAL JOIN GRANDPRIX_DRIVERSTANDING_2"; 
        $sql_drop = "DROP VIEW DRIVERSTANDING";
        $selected_table = 'DRIVERSTANDING'; 

        if (connectToDB()) {
            executePlainSQL($sql_view); 
        }
    } 


    /////////////////
    $selectAttributes = implode(', ', $checked_attributes);
    $sql_projection = "SELECT $selectAttributes FROM $selected_table";

    if (connectToDB()) {
        $projection_result = executePlainSQL($sql_projection); 
        if (isset($sql_drop)) {
            executePlainSQL($sql_drop); 
        }
        oci_commit($db_conn);
        ob_start();
        printResult($projection_result, $selected_table);
        $resultOutput = ob_get_clean();
        echo $resultOutput; 
    }
} 




function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('projectionQueryRequest', $_GET)) {
            handleProjectionRequest(); 
        }
    }
}

if (isset($_GET['projectionSubmit'])) {
    handleGETRequest(); 
}
?>


<html>
    <div class="container-fluid mt-3">
        <h2 class="page-headings mt-3">
          Explore the Database
        </h2>
        <form id="selectTableForm" method="get" action="home.php" onsubmit="toggleAndSubmitForm(); return false;">
            <input type="hidden" id="attributeQueryRequest" name="attributeQueryRequest">
            <div class="row">
                <div class="col">
                    <label for="inputState" class="form-label">Select a table to view:</label>
                    <select name="tableName" id="inputState" class="form-select">
                            <option>Table name...</option>
                            <option disabled><em>Entities:</em></option>
                            <option value="SPONSOR">Sponsors</option>
                            <option value="CONSTRUCTOR">Constructors</option>
                            <option value="TEAMMEMBER">Team Members</option>
                            <option value="CAR">Cars</option>
                            <option value="PARTNER">Partners</option>
                            <option value="CIRCUIT">Circuits</option>
                            <option value="GRANDPRIX">Grand Prix</option>
                            <option value="DRIVER">Drivers</option>
                            <option disabled><em>Relationships:</em></option>
                            <option value="CONSTRUCTORSTANDING">ConstructorStandings</option>
                            <option value="DRIVERSTANDING">DriverStandings</option>
                            <option value="SPONSORS">Sponsors</option>
                            <option value="WORKSWITH">WorksWith</option>
                            <option value="DRIVES">Drives</option>
                            <option value="INRELATIONSHIPWITH">InRelationshipWith</option>
                            <option value="CONSTRUCTORHOLDS">ConstructorHolds</option>
                            <option value="DRIVERHOLDS">DriverHolds</option>
                    </select>
                </div>
            </div>        
            <div class="col-12 mt-3">
                <button type="button" class="btn btn-primary" name="updateSubmit" onclick="toggleAndSubmitForm()">Choose attributes for selected table</button> 
            </div> 
        </form>

        <div id="attributePopUp" class="mt-3 alert alert-danger" style="display: none;">
            <form id="attributeForm" method="GET" action="home.php">
                <h6>Select which attributes to view:</h6>
                <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
                <input type="hidden" id="selectedTable" name="selectedTable" value="">
                <div class="d-inline-flex p-2">
                    <div class="row" id="insertAttributes">

                    </div>
                </div>
                <div class="col-12 mt-3 mt-3">
                        <!-- button that uses ajax -->
                        <!-- <button type="button" onclick="showProjectionResults()" class="btn btn-primary" name="projectionSubmit">View table</button>  -->
                        <button type="submit" class="btn btn-primary" name="projectionSubmit">View table</button> 
                </div>
            </form>  
        </div>
        

        <div id="tablePopUp" class="mt-3 alert alert-danger" style="display: none;">
            
        </div>


    </div>

    <script>
        function showProjectionResults() {
            var checkboxes = document.querySelectorAll('#attributeForm input[type="checkbox"]');
            var checkedAttributes = []; 

            // display the element: 
            var element = document.getElementById("tablePopUp");
            element.style.display = "block";

            checkboxes.forEach(function (checkbox) {
                if(checkbox.checked) {
                    checkedAttributes.push(checkbox.value); 
                }
            })

            document.getElementById('tablePopUp').innerHTML = 'Selected Checkboxes: ' + checkedAttributes.join(', ');
        }

        // only needed if we use ajax 
        function toggleAndSubmitForm() {
            var form = document.getElementById("selectTableForm");
            var selectedOption = form.elements["tableName"].value; 
        
            // display the element: 
            var element = document.getElementById("attributePopUp");
            element.style.display = "block";

            // use AJAX to make a request to PHP (server)
            var xhttp = new XMLHttpRequest(); 
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the HTML element with the response from the server
                    console.log(this.responseText); 
                    document.getElementById("insertAttributes").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "ajax_request.php?action=getTableAttributes&tableName=" + selectedOption, true);
            xhttp.send();

            document.getElementById("selectedTable").value = selectedOption;
        }
    </script>
</html>
