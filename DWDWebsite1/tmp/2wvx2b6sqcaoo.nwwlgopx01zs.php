<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://unpkg.com/konva@8.3.2/konva.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite1/css/navBar.css">
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite1/css/canvasHelp.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Rammetto+One" />

    <title>Konva Modify Shape Color on Click Demo</title>
    <style>
        .styleSection{
            position: absolute;
            padding: 75px;
            height:700px;
            display: flex;
        }
        .bgImage{
            background: url('https://s2250677.edinburgh.domains/DWDWebsite1/images/bg2.jpg');
            height: 700px;
            background-size: contain;
            float: right;
        }
        .ifr{
            text-align: center;
        }
        iframe{
            box-shadow: 5px 5px 5px #888;
        }
    </style>
</head>

<body>

<div>
<nav>
    <ul>
        <li><a href="<?= ($BASE) ?>/"> Home </a></li>
        <li><a href="<?= ($BASE) ?>/style-transfer"> Style Transfer</a></li>
        <li><a href="<?= ($BASE) ?>/canvas">Canvas</a></li>
    </ul>
</nav>
</div>

<div class = "styleSection">
    <div class = "bgImage">
        <h1> hello??</h1>
    </div>

<h1>Make your own cubist portrait!</h1>

<div class ='ifr'>
    <iframe src="https://s2250677.edinburgh.domains/DWDWebsite1/ui/canvasDraw.html" width=500 height=500 loading="lazy" allowfullscreen=""></iframe>
</div>
</div>

<p></p>

<!-- help menu and popup -->
<a class="button" href="#popup1">Help</a>

<div id="popup1" class="overlay">
    <div class="popup">
        <h2>Need some help?</h2>
        <a class="close" href="#">&times;</a>
        <div class="content">
            Welcome to the Canvas tool! Drag and drop the shapes on the canvas to create your own original cubist art!
            <p>
                To delete shapes: Right-click on the shape you want to delete, and select "delete" from the pop-up menu.
            </p>
            <p>
                To resize/rotate shapes: click on the shape and a blue outline will appear. Drag the dots on the borders of the outline to resize, and drag the dot that looks like an antenna to rotate. Click on the whitespace of the canvas to rid of the selection tool.
            </p>
            <p>
                To change shape's color: click on the shape until the desired color appears.
            </p>
        </div>
    </div>
</div>

<script>

</script>
</body>
</html>