<?php 

// Automatically includes files containing classes that are called
spl_autoload_register(function($className)
{
    // parse out filename where class should be located
    list($filename, $suffix) = explode('_',$className);

    // compose file name
    $file = SERVER_ROOT . '/models/' . strtolower($filename) . '.php';

    // fetch file
    if(file_exists($file)){
        // get file
        include_once($file);
    }
    else{
        // file does not exist!
        die("File '$filename' containing class '$className' not found.");
    }

});    

// fetch the passed request
$request = $_SERVER['QUERY_STRING'];

// parse the page request and GET variables
$parsed = explode('&', $request);

// the page is the first element 
$page = array_shift($parsed);

// the rest of the array are GET statements, parse them out
$getVars = array();
foreach($parsed as $argument)
{
    list($variable, $value) = explode('=', $argument);
    $getVars[$variable] = $value;
}

/*
// this is a test, and we will remove it later
print "The page you requested is '$page'";
print '<br/>';
$vars = print_r($getVars, TRUE);
print "The following GET vars were passes to the page:<pre>".$vars."</pre>";
*/

// compute the path to the file 
$target = SERVER_ROOT . '/controllers/' . $page . '.php';

// get target 
if (file_exists($target))
{
    include_once($target);

    // modify page to fit naming convention
    $class = ucfirst($page) . '_Controller';

    // instantiate the appropriate class
    if (class_exists($class))
    {
        $controller = new $class;
    }
    else
    {
        // did we name our class correctly?
        die('class does not exist');
    }
}
else
{
    // can't find the file in 'controllers'! 
    die('page does not exist!');
}

// once we have the controller instantiated, execute the default function
// pass any GET variables to the main method
$controller->main($getVars);