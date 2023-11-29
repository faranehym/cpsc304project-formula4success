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
?>


<html>
    <div>
    
        <form method="POST" action="constructor.php">
                        <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                        <div class="row">
                            <div class="col">
                                <label for="inputState" class="form-label">Select a table to view:</label>
                                <select name="constructorName" id="inputState" class="form-select">
                                        <option disabled>Table name...</option>
                                        <option disabled><em>Entities:</em></option>
                                        <option>Sponsors</option>
                                        <option>Constructors</option>
                                        <option>Team Members</option>
                                        <option>Cars</option>
                                        <option>Partners</option>
                                        <option>Circuits</option>
                                        <option>Grand Prix</option>
                                        <option>Drivers</option>
                                        <option disabled><em>Relationships:</em></option>
                                        <option>ConstructorStandings</option>
                                        <option>DriverStandings</option>
                                        <option>Sponsors</option>
                                        <option>WorksWith</option>
                                        <option>Drives</option>
                                        <option>InRelationshipWith</option>
                                        <option>ConstructorHolds</option>
                                        <option>DriverHolds</option>
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
        
        <form method="GET" action="driver.php">
        <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">

        <h6 class="mt-3">Select which attributes to view:</h6>
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
