<?php
/* The DataDirectory table template */
/* @var $tableDataDirectory array */

$tableDataDirectory = isset($tableDataDirectory) ? $tableDataDirectory : array(
    'dir' => array(
        'virtualAddress' => 0,
        'size' => 0
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
    <title>DataDirectory table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>DataDirectory table</h1>
        </div>
<?php endif; ?>
        <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Directory name</th>
                    <th>Virtual address</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tableDataDirectory as $key => $value): ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo formatDecToHex($value['virtualAddress']); ?></td>
                    <td><?php echo formatDecToHex($value['size']); ?></td>
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