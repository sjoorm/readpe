<?php
require_once 'config.php';
/**
 * Parses a raw little-endian data string to integer
 * @param string $string little-endian raw data string
 * @return int parsed value
 */
function parseLittleEndian($string) {
    $result = 0;
    for($i = strlen($string) - 1; $i >= 0; --$i) {
        $result = $result << 8;
        $result += ord($string[$i]);
    }
    return $result;
}

/**
 * Reads an unsigned byte value as integer
 * @param resource $handle opened file's descriptor
 * @return int read byte value
 */
function readByte($handle) {
    $byte = fread($handle, SIZE_OF_BYTE);
    return ord($byte);
}

/**
 * Reads an unsigned word(2 bytes) value as integer
 * @param resource $handle opened file's descriptor
 * @return int read word value
 */
function readWord($handle) {
    $word = fread($handle, SIZE_OF_WORD);
    return parseLittleEndian($word);
}

/**
 * Reads an unsigned dword(4 bytes) value as integer
 * @param resource $handle opened file's descriptor
 * @return int read dword value
 */
function readDWord($handle) {
    $dword = fread($handle, SIZE_OF_DWORD);
    return parseLittleEndian($dword);
}

/**
 * Reads an unsigned qword(8 bytes) value as integer
 * @param resource $handle opened file's descriptor
 * @return int read qword value
 */
function readQWord($handle) {
    $qword = fread($handle, SIZE_OF_QWORD);
    return parseLittleEndian($qword);
}

?>
