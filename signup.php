<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('layouts/header.php'); ?>
    <title>Sign Up</title>
</head>
<body>

<?php
// Unset all the server side variables
session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import database
include("connection.php");

$fname = $lname = $newemail = $tele = $newpassword = $cpassword = "";
$newemail_err = $tele_err = $newpassword_err = $cpassword_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_registration'])) {
        // Process the registration form
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $name = $fname . " " . $lname;
        $email = $_POST['newemail'];
        $tele = $_POST['tele'];
        $newpassword = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];

        // Validation
        if (empty(trim($email))) {
            $newemail_err = "Please enter your email.";
        }

        if (empty(trim($tele))) {
            $tele_err = "Please enter your mobile number.";
        }

        if (empty(trim($newpassword))) {
            $newpassword_err = "Please enter a new password.";
        }

        if (empty(trim($cpassword))) {
            $cpassword_err = "Please confirm the password.";
        } elseif ($newpassword != $cpassword) {
            $cpassword_err = "Passwords do not match.";
        }

        if (empty($newemail_err) && empty($tele_err) && empty($newpassword_err) && empty($cpassword_err)) {
            $sqlmain = "SELECT * FROM webuser WHERE email = ?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
            } else {
                // Hash the password
                $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);

                // Use prepared statements to prevent SQL injection
                $stmt = $database->prepare("INSERT INTO patient (pemail, pname, ppassword, ptel) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $email, $name, $hashed_password, $tele);
                $stmt->execute();

                // Insert into webuser table
                $stmt = $database->prepare("INSERT INTO webuser VALUES (?, 'p')");
                $stmt->bind_param("s", $email);
                $stmt->execute();

                $_SESSION["user"] = $email;
                $_SESSION["usertype"] = "p";
                $_SESSION["username"] = $fname;

                header('Location: patient/index.php');
                exit();
            }
        }
    }
}
?>


  <!--start wrapper-->
  <div class="wrapper">
    
       <!--start content-->
       <main class="authentication-content">
        <div class="container-fluid">
          <div class="authentication-card">
            <div class="card shadow rounded-0 overflow-hidden">
              <div class="row g-0">
                <div class="col-lg-6 bg-login d-flex align-items-center justify-content-center">
                  <img src="assets/images/error/login-img.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6">
                  <div class="card-body p-4 p-sm-5">
                    <h5 class="card-title">Sign Up</h5>
                    <p class="card-text mb-5">See your growth and get consulting support!</p>
                    <form class="form-body" action="" method="post">
                      <div class="d-grid">
                        <a class="btn btn-white radius-30" href="javascript:;"><span class="d-flex justify-content-center align-items-center">
                            <img class="me-2" src="assets/images/icons/search.svg" width="16" alt="">
                            <span>Sign up with Google</span>
                          </span>
                        </a>
                      </div>
                      <div class="login-separater text-center mb-4"> <span>OR SIGN UP WITH EMAIL</span>
                        <hr>
                      </div>
                        <div class="row g-3">
                          <div class="col-12 ">
                            <label for="fname" class="form-label">First Name</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-person-circle"></i></div>
                              <input type="email" class="form-control radius-30 ps-5" id="fname" name="fname" placeholder="Enter Name">
                            </div>
                          </div>

                          <div class="col-12 ">
                            <label for="lname" class="form-label">Last Name</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-person-circle"></i></div>
                              <input type="email" class="form-control radius-30 ps-5" id="lname" name="lname" placeholder="Enter Name">
                            </div>
                          </div>

                          <div class="col-12 <?php echo (!empty($newemail_err)) ? 'has-error' : ''; ?>">
                            <label for="newemail" class="form-label">Email Address</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                              <input type="email" class="form-control radius-30 ps-5" id="newemail" name="newemail" placeholder="Email Address">
                            </div>
                          </div>

                          <div class="col-12 <?php echo (!empty($tele_err)) ? 'has-error' : ''; ?>">
                            <label for="tele" class="form-label">Mobile Number</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                              <input type="email" class="form-control radius-30 ps-5" id="tele" name="tele" placeholder="Mobile Number">
                            </div>
                          </div>

                          <div class="col-12  <?php echo (!empty($newpassword_err)) ? 'has-error' : ''; ?>">
                            <label for="newpassword" class="form-label">Enter Password</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                              <input type="password" class="form-control radius-30 ps-5" id="newpassword" name="newpassword" placeholder="Enter Password">
                            </div>
                          </div>

                          <div class="col-12 <?php echo (!empty($cpassword_err)) ? 'has-error' : ''; ?>">
                            <label for="cpassword" class="form-label">Enter Password</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                              <input type="password" class="form-control radius-30 ps-5" id="cpassword" name="cpassword" placeholder="Enter Password">
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                              <label class="form-check-label" for="flexSwitchCheckChecked">I Agree to the Trems & Conditions</label>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="d-grid">
                              <button type="submit" value="Register" class="btn btn-primary radius-30">Sign Up</button>
                            </div>
                          </div>
                          <div class="col-12">
                            <p class="mb-0">Already have an account? <a href="login.php">Sign in here</a></p>
                          </div>
                        </div>
                    </form>
                 </div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </main>
        
       <!--end page main-->

  </div>
  <!--end wrapper-->

<?php include('layouts/js.php');  ?>
</body>
</html>
