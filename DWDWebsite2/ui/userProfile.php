<?php
session_start();

if (!isset($_SESSION['loggedin'])){
    header('Location: https://s2250677.edinburgh.domains/DWDWebsite2/login/optionScreen.html');
    exit;
}

$user = $_SESSION['username'];

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
    if ($stmt = $con->prepare('SELECT * FROM UserUploads WHERE Name = ?')) {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js" type="text/javascript"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite2/css/site.css">
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite2/css/cubismGallery.css">
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite2/css/canvasHelp.css">
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite2/css/navBar.css">
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite2/css/modal.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Rammetto+One" />
    <style>
        h2{
            font-family: "Rammetto One", sans-serif;
            margin-top:0px;
        }

        h3{
            font-family: "Rammetto One", sans-serif;
            color: #1c1c1c;
            font-size: 2.5vh;
            font-weight: lighter;
        }

        .myImg{
            transition: transform .2s; /* Animation */
        }
        .myImg:hover {
            transform: scale(1.02);
        }

        .galleryArray{
            background-color: #DBCEBA;
            top: 40vh;
            left: 0;
            right: 0;
            padding: 6px;
            position: absolute;
            text-align: center;
            font-family: "Rammetto One", sans-serif;

        }
        .galleryArray h2{
            color: #1c1c1c;
            text-align: center;
        }

        .grid {
            width: 90%;
            margin-left: 72px;
            padding-top:20px;
            background-color: #DBCEBA;

        }

        .grid-item{
            padding:10px;
        }

        .grid-sizer,
        .grid-item {
            width: 20%;
        }

        .grid-item img {
            padding: 0;
            background: rgb(255, 255, 255);
            border: 2px solid #2a2a2a;
        }

        .logo{
            position: fixed;
            left: 1.8vw;
            top: 3.15vh;
            max-height: 100%;
            max-width: 100%;
            height: 4.5vh;
            z-index: 1001;
        }

        .icon{
            position: fixed;
            right: 4vh;
            z-index: 1001;
            top:2.8vh;
        }

        .greetUser{
            position: fixed;
            right: 11vh;
            z-index: 1001;
            top: 1.6vh;
            color:white;
            font-family: Tahoma, Arial, sans-serif;
        }

        .icon2{
            background-color: #DBCEBA;
            top: 9.8vh;
            left: 0;
            right: 0;
            padding: 0px;
            position: absolute;
            text-align: center;
            font-family: "Rammetto One", sans-serif;
        }

        #icon2{
            height: 30vh;
        }


        .button{
            right:15px;
            position: absolute;
            top:13vh;
        }

        .button:hover {
            color: #ffffff;
        }

        p{
            font-family: Tahoma, Arial, sans-serif;
        }
    </style>
</head>

<body>


<a href="https://s2250677.edinburgh.domains/DWDWebsite2/"><img class = "logo" src="https://s2250677.edinburgh.domains/DWDWebsite2/images/logov2white.png" alt="logo"></a>

<?php print "<p class = \"greetUser\"> $user </p> "; ?>

<a href = "https://s2250677.edinburgh.domains/DWDWebsite2/ui/userProfile.php" class = "icon"> <i class="fas fa-user fa-2x" style="color:white"></i> </a>

<!-- navigation bar -->
<nav>
    <ul>
        <li><a href="https://s2250677.edinburgh.domains/DWDWebsite2/learn"> Learn </a></li>
        <li class="create"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/canvas"> Create</a>
            <ul class="dropdown">
                <li class = "dditem"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/style-transfer"> Style Transfer</a></li>
                <li class = "dditem"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/canvas">Canvas</a></li>
            </ul>
        </li>
        <li class="create"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/masterGallery">Galleries</a>
            <ul class="dropdown">
                <li class = "dditem"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/masterGallery">Masters' Gallery</a></li>
                <li class = "dditem"><a href="https://s2250677.edinburgh.domains/DWDWebsite2/userGallery">Community Gallery</a></li>
            </ul>
        </li>
        <!--<li><a href="{{ @BASE }}/userGallery">Community</a></li> -->
    </ul>
</nav>
<div class = "icon2"> <img id = "icon2" src="https://s2250677.edinburgh.domains/DWDWebsite2/images/icon8.png" onclick = "showInfo(this)"/> </div>

<div class = "galleryArray">
    <?php print "<h2> $user's Artwork </h2> "; ?>
    <?php $images = getUserUploads(trim($_SESSION['username'])); $count = sizeof($images);?>
    <?php print "<h3> Creation Count: $count </h3> "; ?>
    <div class="grid">
        <div class="grid-sizer"></div>
        <?php
        //var_dump($_SESSION['username']);

        if ($count == 0){
            print "<p> No art to display!</p> ";
        }
        $images = getUserUploads(trim($_SESSION['username']));
        foreach ($images as $row){
            ?>
            <div class = "grid-item">
                <img class = "myImg" role="button" src="<?php echo trim($row['ArtURL']); ?>" alt="<?php echo trim($row['ArtName']); ?>" onclick = "showInfo(this)"/>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>

<script type = "text/javascript">

    //display modal
    function showInfo(t) {
        // Get the modal
        const modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        const img = document.getElementsByClassName("myImg");
        const modalImg = document.getElementById("img01");
        const captionText = document.getElementById("caption");
        console.log("clicked");
        modal.style.display = "block";
        modalImg.src = t.src;
        captionText.innerHTML = t.alt;

        // Get the <span> element that closes the modal
        const span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    // external js: isotope.pkgd.js, imagesloaded.pkgd.js

    // init Isotope after all images have loaded
    const $grid = $('.grid').imagesLoaded(function () {
        $grid.isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                columnWidth: '.grid-sizer'
            }
        });
    });
</script>

<div class = "logoutButton">
    <a class="button" href='../login/logout.php'> Log Out </a>
</div>


</body>
</html>
