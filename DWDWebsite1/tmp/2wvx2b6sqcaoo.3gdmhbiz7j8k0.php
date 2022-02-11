<!-- 
A very simple home page View template:
just contains a link to the form that we will
use for collecting data.
 -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Rammetto+One" />

    <link rel="stylesheet" href="./css/navBar.css">
    <title><?= ($html_title) ?></title>

    <style>
        .banner{
            background: url('https://s2250677.edinburgh.domains/DWDWebsite1/images/bg1.jpg') no-repeat center;
            width: 100%;
            height:900px;
            top:0px;
            right:0px;

        }
        h1{
            font-family: "Rammetto One", sans-serif;
            color: #1f1f1f;
            font-size: 13px;
            text-align: center;
        }
        body{
            padding: 75px;
        }
    </style>
</head>

<body>

<div class = "homeNav">
    <nav>
        <ul>
            <li><a href="<?= ($BASE) ?>/"> Home </a></li>
            <li><a href="<?= ($BASE) ?>/style-transfer"> Style Transfer</a></li>
            <li><a href="<?= ($BASE) ?>/canvas">Canvas</a></li>
        </ul>
    </nav>
</div>

<div class = "banner">
    <h1>Hello world I am ciara</h1>

</div>


</body>
</html>