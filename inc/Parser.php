<?php

require_once 'config.php';

/**
 * Static class to parse Portable Executable format files.<br/>
 * <b>Usage</b>: create and open file handler (via <b>fopen</b>) to PE file.<br/>
 * Then use <b>parsePEFile</b> and <b>get</b> methods after.
 *
 * @author sjoorm
 */
class Parser {
    private static $headerMSDOS = null;
    private static $headerCOFF = null;
    private static $headerOptional = null;
    private static $tableDataDirectory = null;
    private static $tableSection = null;
    private static $tableImport = null;
    private static $treeResource = null;
    
    /**
     * Parses a raw little-endian data string to integer
     * @param string $string little-endian raw data string
     * @return int parsed value
     */
    private static function parseLittleEndian($string) {
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
    private static function readByte($handle) {
        $byte = fread($handle, SIZE_OF_BYTE);
        return ord($byte);
    }

    /**
     * Reads an unsigned word(2 bytes) value as integer
     * @param resource $handle opened file's descriptor
     * @return int read word value
     */
    private static function readWord($handle) {
        $word = fread($handle, SIZE_OF_WORD);
        return self::parseLittleEndian($word);
    }

    /**
     * Reads an unsigned dword(4 bytes) value as integer
     * @param resource $handle opened file's descriptor
     * @return int read dword value
     */
    private static function readDWord($handle) {
        $dword = fread($handle, SIZE_OF_DWORD);
        return self::parseLittleEndian($dword);
    }

    /**
     * Reads an unsigned qword(8 bytes) value as integer
     * @param resource $handle opened file's descriptor
     * @return int read qword value
     */
    private static function readQWord($handle) {
        $qword = fread($handle, SIZE_OF_QWORD);
        return self::parseLittleEndian($qword);
    }
    
    /**
     * Reads MSDOS header from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(to 0).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with MSDOS header's data
     */
    private static function readHeaderMSDOS($handle) {
        $e_magic = fread($handle, 2);
        fseek($handle, PE_START);
        $e_lfanew = self::readDWord($handle);
        $result = array(
            'e_magic' => $e_magic, //string
            'e_lfanew' => $e_lfanew
        );
        return $result;
    }

    /**
     * Reads COFF header from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(to the <b>e_lfanew</b> offset from MSDOS header).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with COFF header's data
     */
    private static function readHeaderCOFF($handle) {
        $result = array(
            'signature' => fread($handle, 4), //string
            'machine' => self::readWord($handle),
            'numberOfSections' => self::readWord($handle),
            'timeDateStamp' => self::readDWord($handle),
            'pointerToSymbolTable' => self::readDWord($handle),
            'numberOfSymbols' => self::readDWord($handle),
            'sizeOfOptionalHeader' => self::readWord($handle),
            'characteristics' => self::readWord($handle),
        );
        return $result;
    }

    /**
     * Reads "Optional" header from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(right after COFF header).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Optional header's data
     */
    private static function readHeaderOptional($handle) {
        $magic = self::readWord($handle);
        $standart = array(
            'magic' => $magic,
            'majorLinkerVersion' => self::readByte($handle),
            'minorLinkerVersion' => self::readByte($handle),
            'sizeOfCode' => self::readDWord($handle),
            'sizeOfInitializedData' => self::readDWord($handle),
            'sizeOfUninitializedData' => self::readDWord($handle),
            'addressOfEntryPoint' => self::readDWord($handle),
            'baseOfCode' => self::readDWord($handle),
        );
        if($magic == 0x10B) {
            $standart['baseOfData'] = self::readDWord($handle);
        }
        $addon = array(
            'imageBase' => ($magic == 0x10B ? self::readDWord($handle) : self::readQWord($handle)),
            'sectionAligment' => self::readDWord($handle),
            'fileAligment' => self::readDWord($handle),
            'majorOperatingSystemVersion' => self::readWord($handle),
            'minorOperatingSystemVersion' => self::readWord($handle),
            'majorImageVersion' => self::readWord($handle),
            'minorImageVersion' => self::readWord($handle),
            'majorSubsystemVersion' => self::readWord($handle),
            'minorSubsystemVersion' => self::readWord($handle),
            'win32VersionValue' => self::readDWord($handle),
            'sizeOfImage' => self::readDWord($handle),
            'sizeOfHeaders' => self::readDWord($handle),
            'checkSum' => self::readDWord($handle),
            'subsystem' => self::readWord($handle),
            'dllCharacteristics' => self::readWord($handle),
            'sizeOfStackReserve' => ($magic == 0x10B ? self::readDWord($handle) : self::readQWord($handle)),
            'sizeOfStackCommit' => ($magic == 0x10B ? self::readDWord($handle) : self::readQWord($handle)),
            'sizeOfHeapReserve' => ($magic == 0x10B ? self::readDWord($handle) : self::readQWord($handle)),
            'sizeOfHeapCommit' => ($magic == 0x10B ? self::readDWord($handle) : self::readQWord($handle)),
            'loaderFlags' => self::readDWord($handle),
            'numberOfRvaAndSizes' => self::readDWord($handle),
        );
        $result = array_merge($standart, $addon);
        return $result;
    }

    /**
     * Reads Data Directory entry from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Data Directory entry's data
     */
    private static function readDataDirectory($handle) {
        $result = array(
            'virtualAddress' => self::readDWord($handle),
            'size' => self::readDWord($handle),
        );
        return $result;
    }

    /**
     * Reads Data Directory table from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(right after Optional header).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Data Directory table's data
     */
    private static function readTableDataDirectory($handle) {
        $result = array(
            'exportTable' => self::readDataDirectory($handle),
            'importTable' => self::readDataDirectory($handle),
            'resourceTable' => self::readDataDirectory($handle),
            'exceptionTable' => self::readDataDirectory($handle),
            'certificateTable' => self::readDataDirectory($handle),
            'baseRelocationTable' => self::readDataDirectory($handle),
            'debug' => self::readDataDirectory($handle),
            'architecture' => self::readDataDirectory($handle),
            'globalPtr' => self::readDataDirectory($handle),
            'tlsTable' => self::readDataDirectory($handle),
            'loadConfigTable' => self::readDataDirectory($handle),
            'boundImport' => self::readDataDirectory($handle),
            'iAT' => self::readDataDirectory($handle),
            'delayImportDescriptor' => self::readDataDirectory($handle),
            'clrRuntimeHeader' => self::readDataDirectory($handle),
            'reserved' => self::readDataDirectory($handle),
        );
        return $result;
    }

    /**
     * Reads Section entry from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Section entry's data
     */
    private static function readSection($handle) {
        $result = array(
            'name' => rtrim(fread($handle, 8), "\0"), //string
            'virtualSize' => self::readDWord($handle),
            'virtualAddress' => self::readDWord($handle),
            'sizeOfRawData' => self::readDWord($handle),
            'pointerToRawData' => self::readDWord($handle),
            'pointerToRelocations' => self::readDWord($handle),
            'pointerToLineNumbers' => self::readDWord($handle),
            'numberOfRelocations' => self::readWord($handle),
            'numberOfLineNumbers' => self::readWord($handle),
            'characteristics' => self::readDWord($handle),
        );
        return $result;
    }

    /**
     * Reads Section table from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(offset to Optional header plus 
     * <b>sizeOfOptionalHeader</b> from COFF header).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Section table's data
     */
    private static function readTableSection($handle, $numberOfSections) {
        $result = array();
        for($i = 0; $i < $numberOfSections; ++$i) {
            $section = self::readSection($handle);
            $result[$section['name']] = $section;
        }
        return $result;
    }

    /**
     * Reads Thunk(import) entry from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Thunk entry's data
     */
    private static function readThunkEntry($handle) {
        $result = array(
            'originalFirstThunk' => self::readDWord($handle),
            'timeDateStamp' => self::readDWord($handle),
            'forwarderChain' => self::readDWord($handle),
            'name' => self::readDWord($handle),
            'firstThunk' => self::readDWord($handle),
        );
        return $result;
    }

    /**
     * Reads Import table from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(<b>importTable/virtualAddress</b> 
     * from Data Directory table minus <b>section/virtualAddress</b>(section which contains Import table) plus 
     * <b>section/pointerToRawData</b>).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Import table's data
     */
    private static function readTableImport($handle, $offsetSection, $is64) {
        $result = array();
        $isThunk = true;
        while($isThunk) {
            $thunk = self::readThunkEntry($handle);
            $import = array();
            if($thunk['name'] && ($thunk['originalFirstThunk'] || $thunk['firstThunk'])) {
                $positionCurrent = ftell($handle);

                $nameOfDLL = '';
                $symbol = "\0";
                fseek($handle, $offsetSection + $thunk['name']);
                do {
                    $symbol = fread($handle, 1);
                    if($symbol != "\0") {
                        $nameOfDLL .= $symbol;
                    }
                } while($symbol != "\0");

                $isFunction = true;
                $positionFirstThunk = $offsetSection + $thunk['firstThunk'];
                $positionOriginalFirstThunk = $offsetSection + $thunk['originalFirstThunk'];
                while($isFunction) {
                    $function = array();
                    fseek($handle, ($thunk['originalFirstThunk'] ? $positionOriginalFirstThunk : $positionFirstThunk));
                    $sizeOfAddressOfData = 0;
                    if($is64) {
                        $sizeOfAddressOfData = 8;
                        $function['addressOfData'] = self::readQWord($handle);
                    } else {
                        $sizeOfAddressOfData = 4;
                        $function['addressOfData'] = self::readDWord($handle);
                    }
                    if($function['addressOfData']) {
                        if($function['addressOfData'] & ($is64 ? ORDINAL_MASK_64 :ORDINAL_MASK_32)) {
                            $function['ordinal'] = '#' . ($function['addressOfData'] ^ ($is64 ? ORDINAL_MASK_64 :ORDINAL_MASK_32));
                        } else {
                            $nameOfFunction = '';
                            $symbol = "\0";
                            fseek($handle, $offsetSection + $function['addressOfData']);
                            $function['hint'] = self::readWord($handle);
                            do {
                                $symbol = fread($handle, 1);
                                if($symbol != "\0") {
                                    $nameOfFunction .= $symbol;
                                }
                            } while($symbol != "\0");
                            $function['name'] = $nameOfFunction;
                        }
                        $positionFirstThunk += $sizeOfAddressOfData;
                        $positionOriginalFirstThunk += $sizeOfAddressOfData;
                        $import[] = $function;
                    } else {
                        $isFunction = false;
                    }
                }

                fseek($handle, $positionCurrent);

                $importEntry = array(
                    'thunkEntry' => $thunk,
                    'import' => $import,
                );
                $result[$nameOfDLL] = $importEntry;
            } else {
                $isThunk = false;
            }
        }    
        return $result;
    }

    /**
     * Reads Resource Directory header from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Resource Directory header's data
     */
    private static function readHeaderResourceDirectory($handle) {
        $result = array(
            'characteristics' => self::readDWord($handle),
            'timeDateStamp' => self::readDWord($handle),
            'majorVersion' => self::readWord($handle),
            'minorVersion' => self::readWord($handle),
            'numberOfNameEntries' => self::readWord($handle),
            'numberOfIdEntries' => self::readWord($handle),
        );
        return $result;
    }

    /**
     * Reads Resource Directory entry from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Resource Directory entry's data
     */
    private static function readResourceDirectoryEntry($handle) {
        $result = array();
        $first = self::readDWord($handle);
        if($first & ORDINAL_MASK_32) {
            $result['nameRVA'] = $first ^ ORDINAL_MASK_32;
        } else {
            $result['integerId'] = $first;
        }
        $second = self::readDWord($handle);
        if($second & ORDINAL_MASK_32) {
            $result['subdirectoryRVA'] = $second ^ ORDINAL_MASK_32;
        } else {
            $result['dataEntryRVA'] = $second;
        }
        return $result;
    }

    /**
     * Reads Resource Data entry from PE file.
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Resource Data entry's data
     */
    private static function readResourceDataEntry($handle) {
        $result = array(
            'dataRVA' => self::readDWord($handle),
            'size' => self::readDWord($handle),
            'codepage' => self::readDWord($handle),
            'reserved' => self::readDWord($handle),
        );
        return $result;
    }

    /**
     * Reads Resource tree from PE file.<br/>
     * <p>
     * <b>Important:</b> file's read pointer should be properly set(<b>resourceTable/virtualAddress</b> 
     * from Data Directory table minus <b>section/virtualAddress</b>(section which contains Resource tree) plus 
     * <b>section/pointerToRawData</b>).
     * </p>
     * @param resource $handle opened file's descriptor
     * @return array an associative array with Resource tree's data
     */
    private static function readTreeResource($handle, $offsetBase, $tableResourceVA, $count) {
        $result = array();
        $resourceDH = self::readHeaderResourceDirectory($handle);
        $result['directoryHeader'] = $resourceDH;
        $content = array();
        for($i = 0; $i < $resourceDH['numberOfNameEntries'] + $resourceDH['numberOfIdEntries']; ++$i) {
            $resourceDE = self::readResourceDirectoryEntry($handle);
            $offsetEntry = ftell($handle);
            if(isset($resourceDE['nameRVA'])) {
                fseek($handle, $offsetBase + $resourceDE['nameRVA']);
                $length = self::readWord($handle);
                $nameOfEntry = '';
                for($j = 0; $j < $length; ++$j) {
                    $nameOfEntry .= fread($handle, 1);
                    fread($handle, 1); //multibyte encoding
                }
                $content[$i]['name'] = $nameOfEntry;
            } else {
                $content[$i]['ordinal'] = $resourceDE['integerId'];
            }
            $content[$i]['directoryEntry'] = $resourceDE;
            if(isset($resourceDE['dataEntryRVA'])) {
                fseek($handle, $offsetBase + $resourceDE['dataEntryRVA']);
                $resourceDataEntry = self::readResourceDataEntry($handle);
                $offsetData = $offsetBase + $resourceDataEntry['dataRVA'] - $tableResourceVA;
                fseek($handle, $offsetData);
                $data = fread($handle, $resourceDataEntry['size']);
                $resourceDataEntry['data'] = $data;
                $content[$i]['dataEntry'] = $resourceDataEntry;
            } else {
                fseek($handle, $offsetBase + $resourceDE['subdirectoryRVA']);
                $content[$i] += self::readTreeResource($handle, $offsetBase, $tableResourceVA, $count + 1);
            }
            fseek($handle, $offsetEntry);
            $result['directoryContent'] = $content;
        }
        return $result;
    }

    /**
     * Parses the PE file and dumps the data from it.
     * @param resource $handle handler of the opened PE file
     * @return boolean <b>true</b> if parsing has succeded, <b>false</b> otherwise
     */
    public static function parsePEFile($handle) {
        //MSDOS header
        fseek($handle, 0);
        self::$headerMSDOS = self::readHeaderMSDOS($handle);
        //COFF header
        fseek($handle, self::$headerMSDOS['e_lfanew']);
        self::$headerCOFF = self::readHeaderCOFF($handle);
        //Optional header
        $offsetHeaderOptional = ftell($handle);
        self::$headerOptional = self::readHeaderOptional($handle);
        //Data Directories table
        self::$tableDataDirectory = self::readTableDataDirectory($handle);
        //Sections
        $offsetTableSection = $offsetHeaderOptional + self::$headerCOFF['sizeOfOptionalHeader'];
        fseek($handle, $offsetTableSection);
        self::$tableSection = self::readTableSection($handle, self::$headerCOFF['numberOfSections']);
        //Import table and Resource tree
        $tableImportVA = self::$tableDataDirectory['importTable']['virtualAddress'];
        $treeResourceVA = self::$tableDataDirectory['resourceTable']['virtualAddress'];
        if($tableImportVA) {
            $offsetTableImport = 0;
            $offsetSectionImport = 0;
            $offsetTreeResource = 0;
            foreach(self::$tableSection as $section) {
                if($section['virtualAddress'] <= $tableImportVA && 
                   $tableImportVA < $section['virtualAddress'] + $section['sizeOfRawData']) {
                    $offsetTableImport = $section['pointerToRawData'] + $tableImportVA - $section['virtualAddress'];
                    $offsetSectionImport = $section['pointerToRawData'] - $section['virtualAddress'];
                }
                if($section['virtualAddress'] <= $treeResourceVA && 
                   $treeResourceVA < $section['virtualAddress'] + $section['sizeOfRawData']) {
                    $offsetTreeResource = $section['pointerToRawData'] + $treeResourceVA - $section['virtualAddress'];
                }
            }
            unset($section);
            //Import
            fseek($handle, $offsetTableImport);
            $is64 = self::$headerOptional['magic'] == 0x10B ? false : true;
            self::$tableImport = self::readTableImport($handle, $offsetSectionImport, $is64);
            //Resource
            fseek($handle, $offsetTreeResource);
            self::$treeResource = self::readTreeResource($handle, $offsetTreeResource, $treeResourceVA, 0);
        }
        return (self::$headerMSDOS && self::$headerCOFF && self::$headerOptional && 
           self::$tableDataDirectory && self::$tableSection) ? true : false;
    }
    
    /**
     * Returns MSDOS header
     * @return array MSDOS header as an associative array
     */
    public static function getHeaderMSDOS() {
        return self::$headerMSDOS;
    }
    
    /**
     * Returns COFF header
     * @return array COFF header as an associative array
     */
    public static function getHeaderCOFF() {
        return self::$headerCOFF;
    }
    
    /**
     * Returns Optional header
     * @return array Optional header as an associative array
     */
    public static function getHeaderOptional() {
        return self::$headerOptional;
    }
    
    /**
     * Returns Data Directory table
     * @return array Data Directory table as an associative array
     */
    public static function getTableDataDirectory() {
        return self::$tableDataDirectory;
    }
    
    /**
     * Returns Section table
     * @return array Section table as an associative array
     */
    public static function getTableSection() {
        return self::$tableSection;
    }
    
    /**
     * Returns Import table
     * @return array Import table as an associative array
     */
    public static function getTableImport() {
        return self::$tableImport;
    }
    
    /**
     * Returns Resource tree
     * @return array Resource tree as an associative array
     */
    public static function getTreeResource() {
        return self::$treeResource;
    }
}

?>
