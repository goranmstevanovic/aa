<head>
    
</head>
<?php
// core configuration
include_once "config/core.php";

// set page title
$page_title = "Login";

// include login checker
$require_login=false;
include_once "login_checker1.php";

// default to false
$access_denied=false;

// post code
// if the login form was submitted
if(isset($_POST['logovanje'])){
    // email check
    // include classes
    include_once "config/database.php";
    include_once "objects/user.php";

// get database connection
    $database = new Database();
    $db = $database->getConnection();

// initialize objects
    $user = new User($db);

// check if email and password are in the database
    $user->email=$_POST['email'];

// check if email exists, also get user details using this emailExists() method
    $email_exists = $user->emailExists();

// login validation
    // validate login
    if ($email_exists && password_verify($_POST['password'], $user->password) && $user->status==1){

        // if it is, set the session value to true
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['access_level'] = $user->access_level;
        $_SESSION['firstname'] = htmlspecialchars($user->firstname, ENT_QUOTES, 'UTF-8') ;
        $_SESSION['lastname'] = $user->lastname;

        
          //  header("Location: {$home_url}index.php?action=login_success");
            header("Location: {$home_url}index.php?action=login_success");
        
    }

// if username does not exist or password is wrong
    else{
        $access_denied=true;
      //  $action = 'access_denied';
    }
}
$action=isset($_GET['action']) ? $_GET['action'] : "";
// include page header HTML

    include_once "layout_head.php";



echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";

// alert messages
// get 'action' value in url parameter to display corresponding prompt messages


// tell the user he is not yet logged in
if($access_denied){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>
        Zabranjen pristup.<br /><br />
        Vaše korisničko ime i lozinka ne odgovaraju ni jednom korisniku
    </div>";
}

// tell the user to login
else if($action=='please_login' ){
    echo "<div class='alert alert-info'>
        <strong>Molim Vas da unesete korisničko ime i lozinku.</strong>
    </div>";
}elseif($action =='izbac'){
    echo "<div class='alert alert-info'>
        <strong>Vaša sesija je istekla, pa morate ponovo da se logujete.</strong>
    </div>";
}


// tell the user email is verified
else if($action=='email_verified'){
    echo "<div class='alert alert-success'>
        <strong>Your email address have been validated.</strong>
    </div>";
}elseif($action !='not_yet_logged_in'){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Molim Vas da unesete korisničko ime i lozinku.</div>";
}

// tell the user if access denied
else{

}
if($action !='not_yet_logged_in'){
// actual HTML login form
echo "<div class='account-wall'>";
echo "<div id='my-tab-content' class='tab-content'>";
echo "<div class='tab-pane active' id='login'>";
echo "<img class='profile-img' src='images/login-icon.png'>";
echo "<form class='form-signin' id='logovanje' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
echo "<input type='text' name='email' class='form-control' placeholder='Email' required autofocus />";
echo "<input type='password' name='password' class='form-control' placeholder='Password' required />";
echo "<input type='submit' class='btn btn-lg btn-primary btn-block' name='logovanje' value='Log In' />";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";
}else{
    echo "<div class='alert alert-success'>
    <strong>Neki tekst</strong>
</div>";

}
echo "</div>";

// footer HTML and JavaScript codes
include_once "layout_foot.php";
?>