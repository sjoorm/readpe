<?php
require_once 'inc/parser.php';
require_once 'inc/format.php';

$filePE = array();    
if(isset($_FILES['filePE']) && preg_match('/^.*\.{1}[eE][xX][eE]$/', $_FILES['filePE']['name'])) {
    $filename = $_FILES['filePE']['tmp_name'];
    $handle = fopen($filename, 'rb');
    if($handle) {
        $filePE = parsePEFile($handle);
        fclose($handle);
    } else {
        $filePE = false;
    }
} else {
    $filePE = false;
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
        <?php echo printTableFromArray($filePE['headerMSDOS'], 'bordered'); ?>
        <p>COFF header</p>
        <?php echo printTableFromArray($filePE['headerCOFF'], 'bordered'); ?>
        <p>Optional header</p>
        <?php echo printTableFromArray($filePE['headerOptional'], 'bordered'); ?>
        <p>Data Directory table</p>
        <?php echo printTableFromArray($filePE['tableDataDirectory'], 'bordered'); ?>
        <p>Section table</p>
        <?php echo printTableFromArray($filePE['tableSection'], 'bordered'); ?>
        <p>Import table</p>
        <?php echo printTableFromArray(isset($filePE['tableImport']) ? $filePE['tableImport'] : null, 'bordered'); ?>
        <p>Resource tree</p>
        <?php echo printTableFromArray(isset($filePE['treeResource']) ? $filePE['treeResource'] : null, 'bordered'); ?>
        <?php
            endif;
        ?>
    </body>
</html>