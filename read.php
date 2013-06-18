<?php
require_once 'inc/Parser.php';
require_once 'inc/format.php';

$filePE = false;    
if(isset($_FILES['filePE']) && preg_match('/^.*\.{1}[eE][xX][eE]$/', $_FILES['filePE']['name'])) {
    $filename = $_FILES['filePE']['tmp_name'];
    $handle = fopen($filename, 'rb');
    if($handle) {
        $filePE = Parser::parsePEFile($handle);
        fclose($handle);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Read PE file's data</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
    </head>
    <body>
        <?php
            if(!$filePE):
                header('Location: index.php');
            else:
        ?>
        <p>MSDOS header</p>
        <?php echo printTableFromArray(Parser::getHeaderMSDOS(), 'bordered'); ?>
        <p>COFF header</p>
        <?php echo printTableFromArray(Parser::getHeaderCOFF(), 'bordered'); ?>
        <p>Optional header</p>
        <?php echo printTableFromArray(Parser::getHeaderOptional(), 'bordered'); ?>
        <p>Data Directory table</p>
        <?php echo printTableFromArray(Parser::getTableDataDirectory(), 'bordered'); ?>
        <p>Section table</p>
        <?php echo printTableFromArray(Parser::getTableSection(), 'bordered'); ?>
        <p>Import table</p>
        <?php echo printTableFromArray(Parser::getTableImport(), 'bordered'); ?>
        <p>Resource tree</p>
        <?php echo printTableFromArray(Parser::getTreeResource(), 'bordered'); ?>
        <?php
            endif;
        ?>
    </body>
</html>