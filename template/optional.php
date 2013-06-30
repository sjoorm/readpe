<?php
/* The Optional header template */
/* @var $headerOptional array */

$headerOptional = isset($headerOptional) ? $headerOptional : null;
$isOutputBuffer = (ob_get_level() <= 1) ? false : true;
if(!$isOutputBuffer):
require_once '../inc/config.php';
require_once '../inc/format.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Optional header table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Optional header table</h1>
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
                    <td>magic</td>
                    <td><?php echo $GLOBALS['optionalMagic'][(isset($headerOptional)) ? $headerOptional['magic'] : 0x10b]; ?></td>
                </tr>
                <tr>
                    <td>majorLinkerVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['majorLinkerVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>minorLinkerVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['minorLinkerVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfCode</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfCode'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfInitializedData</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfInitializedData'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfUninitializedData</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfUninitializedData'] : 0); ?></td>
                </tr>
                <tr>
                    <td>addressOfEntryPoint</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['addressOfEntryPoint'] : 0); ?></td>
                </tr>
                <tr>
                    <td>baseOfCode</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['baseOfCode'] : 0); ?></td>
                </tr>
                <?php if(isset($headerOptional) && isset($headerOptional['baseOfData'])): ?>
                <tr>
                    <td>baseOfData</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['baseOfData'] : 0); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>imageBase</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['imageBase'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sectionAligment</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sectionAligment'] : 0); ?></td>
                </tr>
                <tr>
                    <td>fileAligment</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['fileAligment'] : 0); ?></td>
                </tr>
                <tr>
                    <td>majorOperatingSystemVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['majorOperatingSystemVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>minorOperatingSystemVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['minorOperatingSystemVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>majorImageVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['majorImageVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>minorImageVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['minorImageVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>majorSubsystemVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['majorSubsystemVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>minorSubsystemVersion</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['minorSubsystemVersion'] : 0); ?></td>
                </tr>
                <tr>
                    <td>win32VersionValue</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['win32VersionValue'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfImage</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfImage'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfHeaders</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfHeaders'] : 0); ?></td>
                </tr>
                <tr>
                    <td>checkSum</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['checkSum'] : 0); ?></td>
                </tr>
                <tr>
                    <td>subsystem</td>
                    <td><?php echo $GLOBALS['optionalSubsystem'][(isset($headerOptional)) ? $headerOptional['subsystem'] : 0]; ?></td>
                </tr>
                <tr>
                    <td>dllCharacteristics</td>
                    <td>
                        <?php
                            $characteristics = '';
                            $characteristicsValue = (isset($headerOptional)) ? $headerOptional['dllCharacteristics'] : 0;
                            foreach($GLOBALS['optionalDllCharacteristics'] as $key => $value) {
                                if($characteristicsValue & $key) {
                                    $characteristics .= $value . ' ';
                                }
                            }
                            unset($value);
                            echo $characteristics;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>sizeOfStackReserve</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfStackReserve'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfStackCommit</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfStackCommit'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfHeapReserve</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfHeapReserve'] : 0); ?></td>
                </tr>
                <tr>
                    <td>sizeOfHeapCommit</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['sizeOfHeapCommit'] : 0); ?></td>
                </tr>
                <tr>
                    <td>loaderFlags</td>
                    <td><?php echo formatDecToHex((isset($headerOptional)) ? $headerOptional['loaderFlags'] : 0); ?></td>
                </tr>
                <tr>
                    <td>numberOfRvaAndSizes</td>
                    <td><?php echo (isset($headerOptional)) ? $headerOptional['numberOfRvaAndSizes'] : 0; ?></td>
                </tr>
            </tbody>
        </table>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>