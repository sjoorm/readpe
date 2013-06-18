<?php

/**
 * Prints array as html table
 * @param array $array array
 * @param string $class class to be assigned (optional)
 * @return string html code
 */
function printTableFromArray($array, $class = null) {
    $result = '';
    if(!isset($array)) {
        $result = "empty<br/>\n";
    } else {
        $result .= "<table class=\"$class\">\n";
        $result .= "<tr>\n<th>Field</th>\n<th>Value</th>\n</tr>\n";
        foreach($array as $key => $value) {
            $result .= "<tr>\n";
            $result .= "<td>$key</td>\n";
            $result .= "<td>\n";
            if(is_array($value)) {
                $result .= printTableFromArray($value);
            } else {
                $result .= $value;
            }
            $result .= "\n</td>\n";
            $result .= "</tr>\n";
        }
        $result .= "</table>\n";
    }
    return $result;
}

?>
