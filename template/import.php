<?php
/* The Import table template */
/* @var $tableImport array */

$tableImport = isset($tableImport) ? $tableImport : array(
    'user32.dll' => array(
        'thunkEntry' => array(
            'originalFirstThunk' => 0x64d4,
            'timeDateStamp' => 0x0,
            'forwarderChain' => 0x0,
            'name' => 0x668e,
            'firstThunk' => 0x60cc,
        ),
        'import' => array(
            array(
                'addressOfData' => 0x6682,
                'hint' => 0xda,
                'name' => 'EndDialog',
            ),
        ),
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
    <title>Import table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Import table</h1>
        </div>
<?php endif; ?>
        <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Import name</th>
                    <th>Import data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tableImport as $name => $thunkEntry): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td>
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hint</th>
                                    <th>Name/Ord(#)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($thunkEntry['import'] as $key => $value): ?>
                                <tr>
                                    <td><?php echo ($key + 1); ?></td>
                                    <td><?php echo (isset($value['hint'])) ? formatDecToHex($value['hint']) : 'null'; ?></td>
                                    <td><?php echo (isset($value['name'])) ? $value['name'] : $value['ordinal']; ?></td>
                                </tr>
                                <?php endforeach;
                                      unset($value); ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php endforeach;
                      unset($thunkEntry); ?>
            </tbody>
        </table>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>