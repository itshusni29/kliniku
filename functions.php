<?php

// Include the config file to ensure BASE_PATH is defined
include_once 'config.php';

// Function to include files dynamically using the base path
function includeFile($path)
{
    include(BASE_PATH . '/' . $path);
}

?>
