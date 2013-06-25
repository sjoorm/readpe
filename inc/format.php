<?php

function formatValue($value) {
    return (intval($value) == 0 && $value != '0') ? $value : '0x' . dechex($value);
}

/**
 * Prints array as html table
 * @param array $array array
 * @param boolean $recursive <b>TRUE</b> if you want to print inline arrays as tables, <b>FALSE</b> otherwise
 * @param array $params array of params(optional); possible attributes: class to be assigned, caption of the table
 * @return string html code
 */
function printTableFromArray($array, $recursive, $params = null) {
    $result = '';
    $class = isset($params['class']) ? $params['class'] : null;
    $caption = isset($params['caption']) ? $params['caption'] : null;
    if(!isset($array)) {
        $result = "empty\n";
    } else {
        $result .= "<table class=\"$class\">\n";
        if($caption) {
            $result .= "<caption>$caption</caption>\n";
        }
        $result .= "<thead>\n<tr>\n<th>Field</th>\n<th>Value</th>\n</tr>\n</thead>\n<tbody>";
        foreach($array as $key => $value) {
            $result .= "<tr>\n";
            $result .= "<td>$key</td>\n";
            $result .= "<td>\n";
            $recursive = true;
            if(is_array($value)) {
                $result .= ($recursive) ? printTableFromArray($value, $params) : 'array';
            } else {
                $result .= formatValue($value);
            }
            $result .= "\n</td>\n";
            $result .= "</tr>\n";
        }
        $result .= "</tbody>\n</table>\n";
    }
    return $result;
}

?>
