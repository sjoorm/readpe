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
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/read.js"></script>
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
                        <?php
                            $headerMSDOS = Parser::getHeaderMSDOS();
                            ob_start();
                            include 'template/msdos.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabCOFF">
                        <?php
                            $headerCOFF = Parser::getHeaderCOFF();
                            ob_start();
                            include 'template/coff.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabOptional">
                        <?php
                            $headerOptional = Parser::getHeaderOptional();
                            ob_start();
                            include 'template/optional.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabData">
                        <?php
                            $tableDataDirectory = Parser::getTableDataDirectory();
                            ob_start();
                            include 'template/data.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabSection">
                        <?php
                            $tableSection = Parser::getTableSection();
                            ob_start();
                            include 'template/section.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabImport">
                        <?php
                            $tableImport = Parser::getTableImport();
                            ob_start();
                            include 'template/import.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                    <div class="tab-pane" id="tabResource">
                        <?php
                            $treeResource = Parser::getTreeResource();
                            ob_start();
                            include 'template/resource.php';
                            echo ob_get_clean();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
            endif;
        ?>
    </body>
</html>