<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://unpkg.com/konva@8.3.2/konva.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Konva Modify Shape Color on Click Demo</title>
    <style>
        body {
            margin: 10px;
            padding: 10px;
            overflow: hidden;
            background-color: #af2c2c;
        }

        #menu {
            display: none;
            position: absolute;
            width: 60px;
            background-color: white;
            box-shadow: 0 0 5px grey;
            border-radius: 3px;
        }

        #menu button {
            width: 100%;
            background-color: white;
            border: none;
            margin: 0;
            padding: 10px;
        }

        #menu button:hover {
            background-color: lightgray;
        }

    </style>
</head>

<body>

<div id="container"></div>
    <!-- delete menu -->
    <div id="menu">
        <div>
            <button id="delete-button">Delete</button>
        </div>
    </div>

</div>

<script>
    var width = window.innerWidth;
    var height = window.innerHeight;

    var stage = new Konva.Stage({
        container: 'container',
        width: width,
        height: height,
        draggable: false,
    });

    var layer = new Konva.Layer();
    stage.add(layer);

    var tr = new Konva.Transformer();
    layer.add(tr);

    var arr = [];

    //==========================================================================================================
    //shape creator
    function addShapes(shape){
        let sh;
        for (var i = 0; i < 6; i++) {
            if (shape === "tri") {
                sh = new Konva.RegularPolygon({
                    x: 80,
                    y: 50,
                    sides: 3,
                    radius: 50,
                    fill: 'red',
                    stroke: 'black',
                    strokeWidth: 1,
                    draggable: true,
                    opacity: 0.8,
                });
            } else if (shape === "sqr") {
                sh = new Konva.Rect({
                    x: 35,
                    y: 155,
                    fill: 'yellow',
                    stroke: 'black',
                    strokeWidth: 1,
                    draggable: true,
                    width: 75,
                    height: 75,
                    opacity: 0.8,
                });
            } else if (shape === "circ") {
                sh = new Konva.Circle({
                    x: 80,
                    y: 350,
                    radius: 50,
                    fill: 'blue',
                    stroke: 'black',
                    strokeWidth: 1,
                    draggable: true,
                    opacity: 0.8,
                });
            }

            sh.on('click', function () {
                var fill = this.fill() === 'yellow' ? '#00D2FF' : 'yellow';
                this.fill(fill);
            });

            sh.on('dragstart', function () {
                this.moveToTop();
            });

            sh.on('dragmove', function () {
                document.body.style.cursor = 'pointer';
            });

            sh.on('mouseover', function () {
                document.body.style.cursor = 'pointer';
            });
            sh.on('mouseout', function () {
                document.body.style.cursor = 'default';
            });

            layer.add(sh);
            arr.push(sh);
        }
    }
    addShapes("tri");
    addShapes("sqr");
    addShapes("circ");

    tr.nodes(arr);

    //==========================================================================================================
    //resize handler
    var selectionRectangle = new Konva.Rect({
        fill: 'rgba(0,0,255,0.5)',
        visible: false,
    });
    layer.add(selectionRectangle);

    var x1, y1, x2, y2;
    stage.on('mousedown touchstart', (e) => {
        // do nothing if we mousedown on any shape
        if (e.target !== stage) {
            return;
        }
        e.evt.preventDefault();
        x1 = stage.getPointerPosition().x;
        y1 = stage.getPointerPosition().y;
        x2 = stage.getPointerPosition().x;
        y2 = stage.getPointerPosition().y;

        selectionRectangle.visible(true);
        selectionRectangle.width(0);
        selectionRectangle.height(0);
        console.log("mousedown touchstart called");
    });

    stage.on('mousemove touchmove', (e) => {
        // do nothing if we didn't start selection
        if (!selectionRectangle.visible()) {
            return;
        }
        e.evt.preventDefault();
        x2 = stage.getPointerPosition().x;
        y2 = stage.getPointerPosition().y;

        selectionRectangle.setAttrs({
            x: Math.min(x1, x2),
            y: Math.min(y1, y2),
            width: Math.abs(x2 - x1),
            height: Math.abs(y2 - y1),
        });
        console.log("mousemove touchmove called");
    });

    stage.on('mouseup touchend', (e) => {
        // do nothing if we didn't start selection
        if (!selectionRectangle.visible()) {
            return;
        }
        e.evt.preventDefault();
        // update visibility in timeout, so we can check it in click event
        setTimeout(() => {
            selectionRectangle.visible(false);
        });

        var shapes = stage.find('.rect');
        var box = selectionRectangle.getClientRect();
        var selected = shapes.filter((shape) =>
            Konva.Util.haveIntersection(box, shape.getClientRect())
        );
        console.log("mouseup touchend called");
        tr.nodes(selected);
    });

    // clicks should select/deselect shapes
    stage.on('click tap', function (e) {
        // if we are selecting with rect, do nothing
        if (selectionRectangle.visible()) {
            return;
        }

        // if click on empty area - remove all selections
        if (e.target === stage) {
            tr.nodes([]);
            return;
        }

        // do we pressed shift or ctrl?
        const metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
        const isSelected = tr.nodes().indexOf(e.target) >= 0;

        if (!metaPressed && !isSelected) {
            // if no key pressed and the node is not selected
            // select just one
            tr.nodes([e.target]);
        } else if (metaPressed && isSelected) {
            // if we pressed keys and node was selected
            // we need to remove it from selection:
            const nodes = tr.nodes().slice(); // use slice to have new copy of array
            // remove node from array
            nodes.splice(nodes.indexOf(e.target), 1);
            tr.nodes(nodes);
        } else if (metaPressed && !isSelected) {
            // add the node into selection
            const nodes = tr.nodes().concat([e.target]);
            tr.nodes(nodes);
        }
        console.log("click tap called");
    });

    //==========================================================================================================
    //menu handler
    let currentShape;
    var menuNode = document.getElementById('menu');

    document.getElementById('delete-button').addEventListener('click', () => {
        currentShape.destroy();
    });

    window.addEventListener('click', () => {
        // hide menu
        menuNode.style.display = 'none';
    });

    stage.on('contextmenu', function (e) {
        // prevent default behavior
        e.evt.preventDefault();
        if (e.target === stage) {
            // if we are on empty place of the stage we will do nothing
            return;
        }
        currentShape = e.target;
        // show menu
        menuNode.style.display = 'initial';
        var containerRect = stage.container().getBoundingClientRect();
        menuNode.style.top =
            containerRect.top + stage.getPointerPosition().y + 4 + 'px';
        menuNode.style.left =
            containerRect.left + stage.getPointerPosition().x + 4 + 'px';
    });

</script>
</body>
</html>