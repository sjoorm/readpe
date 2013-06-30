<?php
/* The MSDOS header template */
/* @var $headerMSDOS array */

$headerMSDOS = isset($headerMSDOS) ? $headerMSDOS : null;
$isOutputBuffer = (ob_get_level() <= 1) ? false : true;
if(!$isOutputBuffer):
require_once '../inc/config.php';
require_once '../inc/format.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>MSDOS header table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>MSDOS header table</h1>
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
                    <td>e_magic</td>
                    <td><?php echo (isset($headerMSDOS)) ? $headerMSDOS['e_magic'] : 'MZ'; ?></td>
                </tr>
                <tr>
                    <td>e_lfanew</td>
                    <td><?php echo formatDecToHex((isset($headerMSDOS)) ? $headerMSDOS['e_lfanew'] : 0); ?></td>
                </tr>
            </tbody>
        </table>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>