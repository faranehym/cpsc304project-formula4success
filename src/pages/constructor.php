

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

// getConstructorNames(); 
include('main.php'); 
include('constructor.html'); 

function showAverageAmount() {
    
}

function handleUpdateRequest() {
    global $db_conn;  
    
    $constructor_name = $_POST['constructorName'];
    $new_points = $_POST['newPoints'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE Constructor SET  numberOfWins='" . $new_points . "' WHERE constructorName='" . $constructor_name . "'");
    oci_commit($db_conn);
}

function handleAverageRequest() {
    global $db_conn;

    // $sql = "";
    // executePlainSQL($sql);
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

if (isset($_POST['updateSubmit'])) {
    handlePOSTRequest();
} else if (isset($_POST['averageSubmit'])) {
    handlePOSTRequest();
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
                                    <option selected>Constructor name...</option>
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
                            <button type="submit" class="btn btn-primary" name="updateSubmit">Update</button> 
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
                    <form method="POST" action="constructor.php">
                        <input type="hidden" id="averageQueryRequest" name="averageQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Find the average sponsorship amount for constructors by country</label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button onclick="showAverageAmount()" type="submit" class="btn btn-primary" name="averageSubmit">Search</button> 
                        </div> 
                    </form> 
                </div>
            </div>
        </div>
        <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            /some other query
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
            handleConstructorDisplayRequest("Constructor");
        ?>
    </div>

</html>

