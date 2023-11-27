

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
include('driver.html'); 

function handleInsertRequest() {
    global $db_conn;  
    
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $dob = $_POST['dateOfBirth'];
    $nationality = $_POST['nationality'];
    $salary = $_POST['salary'];
    $job = "Driver";
    $num = $_POST['driverNumber'];
    $wins = $_POST['numberOfWins'];
    $podiums = $_POST['numberOfPodiums'];
    $poles = $_POST['numberOfPolePositions'];
    $id = $_POST['employeeId'];

    $check_id = executePlainSQL("SELECT COUNT(*) AS count
                                FROM TeamMember
                                WHERE employeeId = '$id'");

    $row = OCI_Fetch_Array($check_id, OCI_ASSOC);
    $id_count = $row['count']; // not finding the array or making the array

    if ($id_count != 0) {
        echo "Error: Employee ID already exists.";
    } else {
        $sql_tm = "INSERT INTO TeamMember (employeeId, firstName, lastName, nationality, 
                dateOfBirth, salary, job) values ('$id', '$first_name', '$last_name', '$nationality', 
                to_date('$dob', 'YYYY-MM-DD'), '$salary', '$job')";
        $sql_d = "INSERT INTO Driver (employeeId, numberOfPodiums, numberOfWins, driverNumber, 
                numberOfPolePositions) values ('$id', '$podiums', '$wins', '$num', '$poles')";
        executePlainSQL($sql_tm);
        executePlainSQL($sql_d);
        oci_commit($db_conn);
    }
}

function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('insertQueryRequest', $_POST)) {
            handleInsertRequest();
        }            
    }
}

if (isset($_POST['insertSubmit'])) {
    handlePOSTRequest();
} 
?>

<html>
    <div class="accordion mt-3" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Insert Driver
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form method="POST" action="driver.php">
                        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                        <div class="row">
                            <div class="col-md-4">
                                <input name="firstName" type="text" class="form-control" placeholder="First Name" aria-label="First Name">
                            </div>
                            <div class="col-md-4">
                                <input name="lastName" type="text" class="form-control" placeholder="Last Name" aria-label="Last Name">
                            </div>
                            <div class="col-md-4">
                                <input name="dateOfBirth" type="text" class="form-control" placeholder="Date Of Birth (YYYY-MM-DD)" aria-label="Date of Birth">
                            </div>
                            <div class="col-md-3">
                                <input name="nationality" type="text" class="form-control" placeholder="Nationality" aria-label="Nationality">
                            </div>
                            <div class="col-md-3">
                                <input name="salary" type="number" class="form-control" placeholder="Salary" aria-label="Salary">
                            </div>
                            <div class="col-md-3">
                                <input name="driverNumber" type="number" class="form-control" placeholder="Driver Number" aria-label="Driver Number">
                            </div>
                            <div class="col-md-3">
                                <input name="employeeId" type="number" class="form-control" placeholder="Employee ID" aria-label="Employee ID">
                            </div>
                            <div class="col-md-4">
                                <input name="numberOfWins" type="number" class="form-control" placeholder="Number of Wins" aria-label="Number of Wins">
                            </div>
                            <div class="col-md-4">
                                <input name="numberOfPodiums" type="number" class="form-control" placeholder="Number of Podiums" aria-label="Number of Podiums">
                            </div>
                            <div class="col-md-4">
                                <input name="numberOfPolePositions" type="number" class="form-control" placeholder="Number of Pole Positions" aria-label="Number of Pole Positions">
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary" name="insertSubmit">Insert</button> 
                        </div> 
                    </form> 
                </div>
            </div>
        </div>

        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Delete Driver (by Employee ID)
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
            </div>
        </div>
        </div>
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            View Drivers By
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
            </div>
        </div>
        </div>
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Driver Partners
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
            </div>
        </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <?php
            handleDriverDisplayRequest("Driver");
        ?>
    </div>

</html>
