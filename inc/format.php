<?php

/**
 * Converts decimal value to hexadecimal and returns it as string
 * @param integer $value value to be formatted
 * @return string string representation of hex value(with 0x)
 */
function formatDecToHex($value) {
    return sprintf('0x%02X', intval($value));
}

/**
 * Converts all unprintable characters to '.' in input string
 * @param string $data input string
 * @return string formatted string
 */
function formatRawData($data, $limit = 0) {
    $result = '';
    $length = strlen($data);
    $left = '';
    $right = '';
    for($i = 0; $i < (($limit && $limit < $length) ? $limit : $length); ++$i) {
        $ord = ord($data[$i]);
        $left .= sprintf('%02X ', $ord);
        if($ord >= 32 && $ord <= 126) {
            $right .= $data[$i];
        } else {
            $right .= '.';
        }
        if(($i + 1) % 16 == 0 && $i) {
            $result .= sprintf("%08X: %s | %s\n", $i - 15, $left, htmlspecialchars($right));
            $left = '';
            $right = '';
        }
    }
    if($limit < $length) {
        $result .= '...';
    }
    return $result;
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
                $result .= ($recursive) ? printTableFromArray($value, $recursive, array_merge($params, array('caption' => 'content'))) : 'array';
            } else {
                $result .= formatDecToHex($value);
            }
            $result .= "\n</td>\n";
            $result .= "</tr>\n";
        }
        unset($value);
        $result .= "</tbody>\n</table>\n";
    }
    return $result;
}

?>
