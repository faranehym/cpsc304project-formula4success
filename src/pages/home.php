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


// dropdown query
function handleGetTableAttributes($selectedTable) {
    global $db_conn;
    $sql_d = "SELECT DISTINCT COLUMN_NAME
              FROM ALL_TAB_COLUMNS
              WHERE TABLE_NAME = UPPER('$selectedTable')"; // problem -> synchronous calls. 

    if (connectToDB()) {
        echo "<h1>". $selectedTable ."</h1>"; 
        $attributes = executePlainSQL($sql_d);
        printAttributes($attributes);
    }

    oci_commit($db_conn);
}

function printAttributes($result) { // formats SQL request to options 
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
        foreach ($row as $value) {
            echo "<h1>". $value ."</h1>"; 
        }
    }
}

?>


<html>
    <div class="container-fluid mt-3">
        <h2 class="page-headings mt-3">
          Explore the Database
        </h2>
        <form id="selectTableForm" method="" action="">
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
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
        <form>
            <h6>Select which attributes to view:</h6>
            <div class="d-inline-flex p-2">
                <div class="row">
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" name="firstName" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                First name
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="lastName" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Last name
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="dateOfBirth" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Date of birth
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="nationality" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Nationality
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" name="driverNumber" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Driver number
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="employeeId" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Employee ID
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="salary" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Salary
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" name="numberOfWins" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Number of wins
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="numberOfPodiums" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Number of podiums
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="numberOfPolePositions" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Number of pole positions
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 mt-3">
                    <button type="submit" class="btn btn-primary" name="projectionSubmit">Insert</button> 
            </div>
        </form>  
        </div>
    </div>

    <script>
        function toggleAndSubmitForm() {
            var form = document.getElementById("selectTableForm");
            var selectedOption = form.elements["tableName"].value; 
        
            // display the element: 
            var element = document.getElementById("attributePopUp");
            element.style.display = "block";

            // argh do i need to be calling php asynchronously 
            var phpCode = '<?php echo handleGetTableAttributes("' + selectedOption + '"); ?>';
            element.innerHTML = phpCode; 
        }
    </script>
</html>
