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
        <title><?php echo 'PE file\'s data: ' . (isset($_FILES['filePE']['name'])) ? $_FILES['filePE']['name'] : 'Error'; ?></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
        <script src="js/jquery-2.0.2.js"></script>
        <script src="js/bootstrap.js"></script>
    </head>
    <body>
        <?php
            if(!$filePE):
                header('Location: index.php');
            else:
                $class = array('class' => 'table table-condensed table-striped table-bordered table-hover');
        ?>
        <div class="navbar">
            <div class="navbar-inner">
                <a class="brand" href="index.php">Read PE</a>
                <ul class="nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="#">PE file's data</a></li>
                </ul>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="page-header">
                <h1><?php echo $_FILES['filePE']['name']; ?></h1>
            </div>
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tabMSDOS" data-toggle="tab">MSDOS header</a></li>
                    <li class=""><a href="#tabCOFF" data-toggle="tab">COFF header</a></li>
                    <li class=""><a href="#tabOptional" data-toggle="tab">Optional header</a></li>
                    <li class=""><a href="#tabData" data-toggle="tab">Data Directory table</a></li>
                    <li class=""><a href="#tabSection" data-toggle="tab">Section table</a></li>
                    <li class=""><a href="#tabImport" data-toggle="tab">Import table</a></li>
                    <li class=""><a href="#tabResource" data-toggle="tab">Resource tree</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabMSDOS">
                        <?php echo printTableFromArray(Parser::getHeaderMSDOS(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabCOFF">
                        <?php echo printTableFromArray(Parser::getHeaderCOFF(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabOptional">
                        <?php echo printTableFromArray(Parser::getHeaderOptional(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabData">
                        <?php echo printTableFromArray(Parser::getTableDataDirectory(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabSection">
                        <?php echo printTableFromArray(Parser::getTableSection(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabImport">
                        <?php echo printTableFromArray(Parser::getTableImport(), false, $class); ?>
                    </div>
                    <div class="tab-pane" id="tabResource">
                        <?php echo printTableFromArray(Parser::getTreeResource(), false, $class); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            endif;
        ?>
    </body>
</html>