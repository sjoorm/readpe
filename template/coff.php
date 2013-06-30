<?php
/* The COFF header template */
/* @var $headerCOFF array */

$headerCOFF = isset($headerCOFF) ? $headerCOFF : null;
$isOutputBuffer = (ob_get_level() <= 1) ? false : true;
if(!$isOutputBuffer):
require_once '../inc/config.php';
require_once '../inc/format.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>COFF header table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>COFF header table</h1>
        </div>
<?php endif; ?>
        <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>signature</td>
                    <td><?php echo (isset($headerCOFF)) ? $headerCOFF['signature'] : 'PE'; ?></td>
                </tr>
                <tr>
                    <td>machine</td>
                    <td><?php echo $GLOBALS['coffMachine'][(isset($headerCOFF)) ? $headerCOFF['machine'] : 0]; ?></td>
                </tr>
                <tr>
                    <td>numberOfSections</td>
                    <td><?php echo (isset($headerCOFF)) ? $headerCOFF['numberOfSections'] : 0; ?></td>
                </tr>
                <tr>
                    <td>timeDateStamp</td>
                    <td><?php echo date('Y-m-d H:i:s', (isset($headerCOFF)) ? $headerCOFF['timeDateStamp'] : 0); ?></td>
                </tr>
                <tr>
                    <td>pointerToSymbolTable</td>
                    <td><?php echo formatDecToHex((isset($headerCOFF)) ? $headerCOFF['pointerToSymbolTable'] : 0); ?></td>
                </tr>
                <tr>
                    <td>numberOfSymbols</td>
                    <td><?php echo formatDecToHex((isset($headerCOFF)) ? $headerCOFF['numberOfSymbols'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfOptionalHeader</td>
                    <td><?php echo formatDecToHex((isset($headerCOFF)) ? $headerCOFF['sizeOfOptionalHeader'] : 0); ?></td>
                </tr>
                <tr>
                    <td>characteristics</td>
                    <td>
                        <?php
                            $characteristics = '';
                            $characteristicsValue = (isset($headerCOFF)) ? $headerCOFF['characteristics'] : 0;
                            foreach($GLOBALS['coffCharacteristics'] as $key => $value) {
                                if($characteristicsValue & $key) {
                                    $characteristics .= $value . ' ';
                                }
                            }
                            unset($value);
                            echo $characteristics;
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>