<?php
/* The Resource tree template */
/* @var $treeResource array */

$treeResource = isset($treeResource) ? $treeResource : 
array (
  'directoryHeader' => 
    array (
      'characteristics' => 0,
      'timeDateStamp' => 0,
      'majorVersion' => 4,
      'minorVersion' => 0,
      'numberOfNameEntries' => 1,
      'numberOfIdEntries' => 1,
        ),
  'directoryContent' => 
    array (
      0 => 
        array (
          'name' => 'PYTHONSCRIPT',
          'directoryEntry' => 
            array (
              'nameRVA' => 160,
              'subdirectoryRVA' => 32,
                ),
          'directoryHeader' => 
            array (
              'characteristics' => 0,
              'timeDateStamp' => 0,
              'majorVersion' => 4,
              'minorVersion' => 0,
              'numberOfNameEntries' => 0,
              'numberOfIdEntries' => 1,
                ),
          'directoryContent' => 
            array (
              0 => 
                array (
                  'ordinal' => 1,
                  'directoryEntry' => 
                    array (
                      'integerId' => 1,
                      'subdirectoryRVA' => 80,
                        ),
                  'directoryHeader' => 
                    array (
                      'characteristics' => 0,
                      'timeDateStamp' => 0,
                      'majorVersion' => 4,
                      'minorVersion' => 0,
                      'numberOfNameEntries' => 0,
                      'numberOfIdEntries' => 1,
                        ),
                  'directoryContent' => 
                    array (
                      0 => 
                        array (
                          'ordinal' => 0,
                          'directoryEntry' => 
                            array (
                              'integerId' => 0,
                              'dataEntryRVA' => 128,
                                ),
                          'dataEntry' => 
                            array (
                              'dataRVA' => 32956,
                              'size' => 3210,
                              'codepage' => 1252,
                              'reserved' => 0,
                              'data' => '4Vx��������',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
      1 => 
        array (
          'ordinal' => 24,
          'directoryEntry' => 
            array (
              'integerId' => 24,
              'subdirectoryRVA' => 56,
                ),
          'directoryHeader' => 
            array (
              'characteristics' => 0,
              'timeDateStamp' => 0,
              'majorVersion' => 4,
              'minorVersion' => 0,
              'numberOfNameEntries' => 0,
              'numberOfIdEntries' => 1,
                ),
          'directoryContent' => 
            array (
              0 => 
                array (
                  'ordinal' => 1,
                  'directoryEntry' => 
                    array (
                      'integerId' => 1,
                      'subdirectoryRVA' => 104,
                        ),
                  'directoryHeader' => 
                    array (
                      'characteristics' => 0,
                      'timeDateStamp' => 0,
                      'majorVersion' => 4,
                      'minorVersion' => 0,
                      'numberOfNameEntries' => 0,
                      'numberOfIdEntries' => 1,
                        ),
                  'directoryContent' => 
                    array (
                      0 => 
                        array (
                          'ordinal' => 0,
                          'directoryEntry' => 
                            array (
                              'integerId' => 0,
                              'dataEntryRVA' => 144,
                                ),
                          'dataEntry' => 
                            array(
                              'dataRVA' => 36168,
                              'size' => 600,
                              'codepage' => 1252,
                              'reserved' => 0,
                              'data' => '<assembly xmlns="fdgdfgdgdfgdfgdfgdfgd',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );

/**
 * Prints resource tree as HTML to string recursively
 * @param array $treeResource resource tree containing array
 * @param array $css styles for HTML elements(box, title, content and data elements)
 * @param integer $level level of recursion(for proper indents)
 * @return string HTML code as string
 */
function printResourceTree($treeResource, $css = array('box' => null, 'title' => null, 'content' => null, 'data' => null), $level = 0) {
    $result = '';
    $tab = '';
    for($i = 0; $i < $level; ++$i) {
        $tab .= '    ';
    }
    $content = '';
    $icon = '';
    $nameOrOrdinal = (isset($treeResource['name'])) ? $treeResource['name'] : 
                     ((isset($treeResource['ordinal'])) ? $treeResource['ordinal'] : 'root');
    if(isset($treeResource['dataEntry'])) {
        $data = formatRawData($treeResource['dataEntry']['data'], 256);
        $content .= "$tab    <ul class=\"{$css['content']} {$css['data']}\">\n$tab        <pre>$data</pre>\n$tab    </ul>\n";
        $icon = "<i class=\"icon-plus\"></i>";
    } else {
        $icon = "<i class=\"icon-folder-close\"></i>";
    }
    if(isset($treeResource['directoryHeader'])) {
        $content .= "$tab    <ul class=\" {$css['content']}\">\n";
        foreach($treeResource['directoryContent'] as $value) {
            $content .= printResourceTree($value, $css, $level + 2);
        }
        unset($value);
        $content .= "$tab    </ul>\n";
    }
    $result .= "$tab<div class=\"{$css['box']}\">\n";
    $result .= "$tab<li class=\" {$css['title']}\">$icon {$nameOrOrdinal}</li>\n";
    $result .= "$content";
    $result .= "$tab</div>\n";
    return $result;
}

$isOutputBuffer = (ob_get_level() <= 1) ? false : true;
if(!$isOutputBuffer):
require_once '../inc/config.php';
require_once '../inc/format.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resource tree</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen"/>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/read.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1>Resource tree</h1>
        </div>
        <button type="button" class="btn"><i class="icon-align-justify"></i></button>
<?php endif; ?>
        <ul class="treeBox">
        <?php echo printResourceTree($treeResource, array(
                'box' => 'treeBox',
                'title' => 'treeTitle',
                'content' => 'treeContent',
                'data' => 'treeData',
            )); ?>
        </ul>
<?php if(!$isOutputBuffer): ?>
    </div>
</body>
</html>
<?php endif; ?>