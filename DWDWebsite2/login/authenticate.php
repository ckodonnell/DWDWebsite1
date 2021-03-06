<?php
//USER LOGIN AND AUTHENTICATION (from here: https://codeshack.io/secure-login-csystem-php-mysql/#creatingthehomepage )

$samesite = 'lax';
//session_set_cookie_params((60*60*24*31), '/', '.s2250677.edinburgh.domains'); //setting for 31 days

session_set_cookie_params([
    'lifetime' => (60*60*24*31),
    'path' => '/',
    'samesite' => $samesite
]);
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'sedin110_user2';
$DATABASE_PASS = 'pEDK9!GK,ZCQ';
$DATABASE_NAME = 'sedin110_GalleryData';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//Username and password checking
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
    //echo 'Incorrect!';

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: https://s2250677.edinburgh.domains/DWDWebsite2/');
            exit();
        } else {
            // Incorrect password
            echo 'Incorrect password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username!';
    }

    $stmt->close();
}

