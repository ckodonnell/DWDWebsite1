<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.12.0/dist/tf.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


<html lang="en">

<head>
    <meta charset=utf-8 />
    <title></title>
    <link rel="stylesheet" href="https://s2250677.edinburgh.domains/DWDWebsite1/css/navBar.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Rammetto+One" />

    <style>
        body{
            padding: 75px;
        }

        h1 {
            text-align: center;
            font-family: Tahoma, Arial, sans-serif;
            color: #a853ce;
            margin: 30px 0;
        }

        div.container {
            display:inline-block;
            padding:
        }

        label {
            text-align:center;
        }

        canvas {
            text-align:center;
        }

        input {
            text-align:center;
        }

        select {
            text-align:center;
        }
    </style>
</head>

<body>
<!-- navigation bar -->
<div>
    <nav>
        <ul>
            <li><a href="<?= ($BASE) ?>/"> Home </a></li>
            <li><a href="<?= ($BASE) ?>/style-transfer"> Style Transfer</a></li>
            <li><a href="<?= ($BASE) ?>/canvas">Canvas</a></li>
        </ul>
    </nav>
</div>

<!-- style transfer -->
<h1> Enter an image and see it transformed into a cubist style! </h1>
<div class="container">
    <label>Choose an image to apply styles to:</label>
    <p></p>
    <canvas id="image" url="/image.jpeg" width="348" height="348"></canvas>
    <p></p>
    <input type="file"
           id="changeImage" name="changeImage"
           accept="image/png, image/jpeg" imgid="image" >
</div>

<div class="container">
    <label>Choose the style image:</label>
    <p></p>
    <canvas id="styleImage"  url="/style_transfer_image.jpeg"  width="348" height="348"></canvas>
    <p></p>
    <!-- <input type="file"
           id="changeStyleImage" name="changeImage"
           accept="image/png, image/jpeg" imgid="styleImage" > -->

    <select id = "changeStyleImage" name = "changeImage" class="centered custom-select" >
        <option value="disabled"> Select style image </option>
        <option value="belin"> Belin </option>
        <option value="gueridon"> Gueridon </option>
        <option value="lady"> Lady </option>
        <option value="portrait"> Portrait </option>
    </select>

</div>

<div class="container">
    <label>See the style transfer!</label>
    <p></p>
    <canvas id="stylizedImage" width="348" height="348"></canvas>
    <p></p>
    <button type="button" name="doStyleTransfer" onclick="doStyleTransfer()">Apply Style Transfer</button>
</div>


</body>
</html>


<script>
    window.addEventListener('load', initStyleTransfer());

  let changeImageUser = function (evt) {
      const tgt = evt.target || window.EventTarget,
          files = tgt.files;

      // FileReader support
    if (FileReader && files && files.length) {
        const fr = new FileReader();
        fr.onload = function () {
        let targetCanvas = document.getElementById(evt.target.getAttribute("imgid"));
        resizeAndSquareImage(fr.result, targetCanvas);

      }
      fr.readAsDataURL(files[0]);

      //=doStyleTransfer();
    }

    // Not supported
    else {
      // fallback -- perhaps submit the input to an iframe and temporarily store
      // them on the server until the user's session ends.
    }
  }

    let changeImageCPU = function (evt) {
        let targetCanvas = document.getElementById("styleImage");

        const art = document.getElementById("changeStyleImage");
        const artSelected = art.value;
        const option = new Image();

        if (artSelected === "belin"){
            option.src = 'https://s2250677.edinburgh.domains/DWDWebsite1/images/BelinUpdate.jpg';
        }
        else if (artSelected === "gueridon"){
            option.src = 'https://s2250677.edinburgh.domains/DWDWebsite1/images/gueridon.jpg';
        }
        else if (artSelected === "lady"){
            option.src = 'https://s2250677.edinburgh.domains/DWDWebsite1/images/lady.jpg';
        }
        else if (artSelected === "portrait"){
            option.src = 'https://s2250677.edinburgh.domains/DWDWebsite1/images/picassoportrait.jpg';
        }

        console.log("here1 is called");

        const ctx = targetCanvas.getContext("2d");

        option.onload = function(){
            //ctx.drawImage(option, 0, 0);
            resizeAndSquareImage(option.src, targetCanvas);
        }


        console.log("here2 is called");

    }

  let fastStyleTransferModel;

  document.getElementById("changeImage").onchange = changeImageUser;
  <!-- document.getElementById("changeStyleImage").onchange = changeImageCPU(); -->
  document.getElementById("changeStyleImage").addEventListener("change", changeImageCPU);

  async function initStyleTransfer() {
    let imageCanvas = document.getElementById("image");
    let styleImageCanvas = document.getElementById("styleImage");

    let imageUrl = imageCanvas.getAttribute("url");
    let imageStyleUrl = styleImageCanvas.getAttribute("url");
    await resizeAndSquareImage(imageUrl, imageCanvas);
    await resizeAndSquareImage(imageStyleUrl, styleImageCanvas);

  }

  async function loadModel() {
      return tf.loadGraphModel('https://s2250677.edinburgh.domains/DWDWebsite1/modelfiles/model.json');
  }

  async function doStyleTransfer() {
    console.log("doStyleTransfer called");
    // load fast style transfer model only per page load
    if (!fastStyleTransferModel) {
      fastStyleTransferModel = await loadModel();
    }
    // get image to apply style to
    const image = document.getElementById("image");
    const styleImage = document.getElementById("styleImage");

    console.log("model=" + fastStyleTransferModel);

    let imageTensor = preprocess(image);
    let styleImageTensor = preprocess(styleImage);
    imageTensor = tf.pool(imageTensor, 3, 'avg', 'same', [1, 1], 1);
    styleImageTensor = tf.pool(styleImageTensor, 3, 'avg', 'same', [1, 1], 1);

    let result = fastStyleTransferModel.execute([imageTensor, styleImageTensor]);
    let canvas = document.getElementById("stylizedImage");

    tf.browser.toPixels( tf.squeeze(result), canvas);
  }

  function preprocess(imageData) {
    let imageTensor = tf.browser.fromPixels(imageData);
    const offset = tf.scalar(255.0);
    const normalized = imageTensor.div(offset);
    const batched = normalized.expandDims(0);
    batched.print();
    return batched;

  }


  function resizeAndSquareImage(imageUrl, targetCanvas) {
    // the desired aspect ratio of our output image (width / height)
    const outputImageAspectRatio = 1;

    // this image will hold our source image data
    const inputImage = new Image();
    console.log("resizeAndSquareImage called");
    // we want to wait for our image to load
    let result = inputImage.onload = () => {
      const imgSize = Math.min(inputImage.width, inputImage.height);
      // The following two lines yield a central based cropping.
      // They can both be amended to be 0, if you wish it to be
      // a left based cropped image.
      const left = (inputImage.width - imgSize) / 2;
      const top = (inputImage.height - imgSize) / 2;
      //var left = 0; // If you wish left based cropping instead.
      //var top = 0; // If you wish left based cropping instead.
      const ctx = targetCanvas.getContext("2d");
      ctx.drawImage(inputImage, left, top, imgSize, imgSize, 0, 0, targetCanvas.width, targetCanvas.height);
      return targetCanvas;
    };
    inputImage.src = imageUrl;
    return result;
  }


</script>