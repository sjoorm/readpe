<?php
/* The Section table template */
/* @var $tableSection array */

$tableSection = isset($tableSection) ? $tableSection : array(
    '.code' => array(
        'name' => '.code',
        'virtualSize' => 0,
        'virtualAddress' => 0,
        'sizeOfRawData' => 0,
        'pointerToRawData' => 0,
        'pointerToRelocations' => 0,
        'pointerToLineNumbers' => 0,
        'numberOfRelocations' => 0,
        'numberOfLineNumbers' => 0,
        'characteristics' => 0,
    )
);
$isOutputBuffer = (ob_get_level() <= 1) ? false : true;
if(!$isOutputBuffer):
require_once '../inc/config.php';
require_once '../inc/format.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Section table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Section table</h1>
        </div>
<?php endif; ?>
        <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Section name</th>
                    <th colspan="2">Section info</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tableSection as $key => $value): ?>
                <tr>
                    <td rowspan="<?php echo count($value) - 1; ?>"><?php echo $key; ?></td>
                    <td>virtualSize</td>
                    <td><?php echo formatDecToHex($value['virtualSize']); ?></td>
                </tr>
                <tr>
                    <td>virtualAddress</td>
                    <td><?php echo formatDecToHex($value['virtualAddress']); ?></td>
                </tr>
                <tr>
                    <td>sizeOfRawData</td>
                    <td><?php echo formatDecToHex($value['sizeOfRawData']); ?></td>
                </tr>
                <tr>
                    <td>pointerToRawData</td>
                    <td><?php echo formatDecToHex($value['pointerToRawData']); ?></td>
                </tr>
                <tr>
                    <td>pointerToRelocations</td>
                    <td><?php echo formatDecToHex($value['pointerToRelocations']); ?></td>
                </tr>
                <tr>
                    <td>pointerToLineNumbers</td>
                    <td><?php echo formatDecToHex($value['pointerToLineNumbers']); ?></td>
                </tr>
                <tr>
                    <td>numberOfRelocations</td>
                    <td><?php echo formatDecToHex($value['numberOfRelocations']); ?></td>
                </tr>
                <tr>
                    <td>numberOfLineNumbers</td>
                    <td><?php echo formatDecToHex($value['numberOfLineNumbers']); ?></td>
                </tr>
                <tr>
                    <td>characteristics</td>
                    <td>
                        <?php
                            $characteristics = '';
                            foreach($GLOBALS['sectionCharacteristics'] as $key => $characteristicsValue) {
                                if($value['characteristics'] & $key) {
                                    $characteristics .= $characteristicsValue . ' ';
                                }
                            }
                            unset($characteristicsValue);
                            echo $characteristics;
                        ?>
                    </td>
                </tr>
                <?php endforeach;
                      unset($value); ?>
            </tbody>
        </table>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>