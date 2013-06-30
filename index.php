<?php

require_once 'inc/config.php';

$maxFileSize = 0;
$maxFileSizeString = ini_get('upload_max_filesize');
$matches = array();
preg_match('/^([0-9]+)([KMG]?)$/', $maxFileSizeString, $matches);
if(count($matches) > 2) {
    $size = intval($matches[1]);
    switch ($matches[2]) {
        case 'K':
            $maxFileSize = $size * SIZE_OF_KILOBYTE;
            break;
        case 'M':
            $maxFileSize = $size * SIZE_OF_MEGABYTE;
            break;
        case 'G':
            $maxFileSize = $size * SIZE_OF_GIGABYTE;
            break;
        default:
            $maxFileSize = $size;
            break;
    }
} else {
    $maxFileSize = intval($maxFileSizeString);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Read PE file's data</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
    </head>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <a class="brand" href="index.php">Read PE</a>
                <ul class="nav">
                    <li class="active"><a href="index.php">Home</a></li>
                </ul>
            </div>
        </div>

        <div class="container well">
            <div class="page-header">
                <h1>Read PE file's data</h1>
            </div>
            <form enctype="multipart/form-data" action="read.php" method="POST">
                <fieldset>
                    <legend>Select a PE file to read</legend>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize; ?>" />
                    <label>Selected file: <input name="filePE" type="file" /></label>
                    <span class="help-block">Maximal allowed file size is <?php echo $maxFileSize / SIZE_OF_MEGABYTE; ?> megabytes.</span>
                    <button type="submit" class="btn">Send file</button>
                </fieldset>
            </form>
        </div>
    </body>
</html>