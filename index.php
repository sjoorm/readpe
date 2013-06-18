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
    </head>
    <body>
        <form enctype="multipart/form-data" action="read.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize; ?>" />
            Parse PE file: <input name="filePE" type="file" />
            <input type="submit" value="Send File" />
        </form>
    </body>
</html>