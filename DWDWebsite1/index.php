<?php
//==============================================================================
// index.php for SimpleExample app //

// Create f3 object then set various global properties of it
// These are available to the routing code below, but also to any
// classes defined in autoloaded definitions

$home = '/home/'.get_current_user();

$f3 = require($home.'/AboveWebRoot/fatfree-master/lib/base.php');

// autoload Controller class(es) and anything hidden above web root, e.g. DB stuff
$f3->set('AUTOLOAD','autoload/;'.$home.'/AboveWebRoot/autoload/');

$db = DatabaseConnection::connect(); // defined as autoloaded class in AboveWebRoot/autoload/
$f3->set('DB', $db);

$f3->set('DEBUG',3);		// set maximum debug level
$f3->set('UI','ui/');		// folder for View templates
header('Access-Control-Allow-Origin: http://s2250677.edinburgh.domains/DWDWebsite1/');

/**
 * CORS
 */
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}

//==============================================================================
// Simple Example URL application routings

//home page (styletransfer.html) -- actually just shows form entry page with a different title

$f3->route('GET /',
    function ($f3)
    {
        $f3->set('html_title','Cubism');
        $f3->set('content','simpleHome.html');
        echo template::instance()->render('layout.html');
    }
);

//==============================================================================
// When using GET, provide a form for the user to upload an image via the file input type
$f3->route('GET /simpleform',
    function($f3)
    {
        $f3->set('html_title','Simple Input Form');
        $f3->set('content','simpleform.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
// When using POST (e.g.  form is submitted), invoke the controller, which will process
// any data then return info we want to display. We display
// the info here via the response.html template
$f3->route('POST /simpleform',
    function($f3)
    {
        $formdata = array();			// array to pass on the entered data in
        $formdata["name"] = $f3->get('POST.name');			// whatever was called "name" on the form
        $formdata["colour"] = $f3->get('POST.colour');		// whatever was called "colour" on the form
        $controller = new SimpleController('simpleModel');
        $controller->putIntoDatabase($formdata);

        $f3->set('formData',$formdata);		// set info in F3 variable for access in response template
        $f3->set('html_title','Simple Example Response');
        $f3->set('content','response.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
$f3->route('GET /dataView',
    function($f3)
    {
        $controller = new SimpleController('simpleModel');
        $alldata = $controller->getData();

        $f3->set("dbData", $alldata);
        $f3->set('html_title','Viewing the data');
        $f3->set('content','dataView.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
$f3->route('GET /editView',				// exactly the same as dataView, apart from the template used
    function($f3)
    {
        $controller = new SimpleController('simpleModel');
        $alldata = $controller->getData();

        $f3->set("dbData", $alldata);
        $f3->set('html_title','Viewing the data');
        $f3->set('content','editView.html');
        echo template::instance()->render('layout.html');
    }
);
//==============================================================================
$f3->route('POST /editView',
    function($f3)
    {
        $controller = new SimpleController('simpleModel');
        $controller->deleteFromDatabase($f3->get('POST.toDelete'));		// in this case, delete selected data record
        $f3->reroute();
    }
);

//==============================================================================

$f3->route('GET /about',
    function($f3)
    {
        $file = F3::instance()->read('README.md');
        $html = Markdown::instance()->convert($file);
        $f3->set('article_html', $html);
        $f3->set('content','article.html');
        echo template::instance()->render('layout.html');;
    }
);


//==============================================================================
function pprint_var($var)
{
    ob_start();
    var_dump($var);
    return ob_get_clean();
}

$f3->set('ONERROR', // what to do if something goes wrong.
    function($f3) {
        $f3->set('html_title',$f3['ERROR']['code']);
        $f3->set('DUMP', pprint_var($f3['ERROR']));
        $f3->set('content','error.html');
        echo template::instance()->render('layout.html');
    }
);
$test = $_SERVER['HOME'];
//==============================================================================
$f3->route('GET /test', // have a test page to print out variables
    function($f3)
    {
        global $test;
        $f3->set('html_title','TEST');
        $f3->set('DUMP', pprint_var($f3));
        $f3->set('content','error.html');
        echo template::instance()->render('layout.html');;
    }
);
//==============================================================================

$f3->route('GET /style-transfer',
    function ($f3)
    {
        $f3->set('html_title','Style Transfer');
        $f3->set('content','styletransfer.html');
        $f3->set('mode', 'no-cors');
        echo template::instance()->render('layout.html');
    }
);

$f3->route('GET /canvas',
    function ($f3)
    {
        $f3->set('html_title','Canvas');
        $f3->set('content','canvas.html');
        echo template::instance()->render('layout.html');
    }
);

$f3->route('GET /learn',
    function ($f3)
    {
        $f3->set('html_title','Learn');
        $f3->set('content','cubismGallery.html');
        echo template::instance()->render('layout.html');
    }
);

$f3->route('GET /canvasDraw',
    function ($f3)
    {
        $f3->set('html_title','CanvasDraw');
        $f3->set('content','canvasDraw.html');
        echo template::instance()->render('layout.html');
    }
);

$f3->route('POST /canvasDraw',
    function($f3)
    {
        $formdata = array();			// array to pass on the entered data in
        $formdata["ArtworkName"] = $f3->get('POST.artName');			// whatever was called "name" on the form
        $formdata["Name"] = $f3->get('POST.yourName');		// whatever was called "colour" on the form
        $formdata["ArtworkURL"] = $f3->get('POST.artURL');		// whatever was called "artURL" on the form
        $controller = new SimpleController('UserUploads');
        $controller->putIntoDatabase($formdata);

        $f3->set('formData',$formdata);		// set info in F3 variable for access in response template
        $f3->set('html_title','CanvasDraw');
        $f3->set('content','uploadComplete.html');
        echo template::instance()->render('layout.html');
    }
);



//==============================================================================
// Run the FFF engine //
$f3->run();
