<?php
session_start();

if (!isset($_SESSION['loggedin'])){
    header('Location: https://s2250677.edinburgh.domains/DWDWebsite2/login/optionScreen.html');
    exit;
}

function getUserUploads($username)
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'sedin110_user2';
    $DATABASE_PASS = 'pEDK9!GK,ZCQ';
    $DATABASE_NAME = 'sedin110_GalleryData';
    // Try and connect using the info above.
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $images = array();
    if ($stmt = $con->prepare('SELECT ArtURL FROM UserUploads WHERE Name = ?')) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        //$stmt->store_result();

        /* Get the result */
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
            //$url = $row["ArtURL"];
            //echo $url;
            //print"<img src=\" $url\" width=\"100px\" height=\"100px\"\/>";
        }
    }
    return $images;
}
?>

<html lang="en">

<body>


<p> hey</p>


<div class = "galleryArray">
    <h2>User Artwork</h2>
    <div class="grid">
        <div class="grid-sizer"></div>
        <?php
            //var_dump($_SESSION['username']);
            $images = getUserUploads(trim($_SESSION['username']));
            foreach ($images as $row){
                ?>
                    <div class = "grid-item">
                        <img src="<?php echo trim($row['ArtURL']); ?>" />
                    </div>
                <?php
            }
        ?>
    </div>
</div>
</div>

<div class = "logoutButton">
    <button onclick="location.href = '../login/logout.php';"> Log Out </button>
</div>

</body>
</html>
