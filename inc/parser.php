<?php
require_once 'config.php';
require_once 'util.php';

/**
 * Reads MSDOS header from PE file.<br/>
 * <p>
 * <b>Important:</b> file's read pointer should be properly set(to 0).
 * </p>
 * @param resource $handle opened file's descriptor
 * @return array an associative array with MSDOS header's data
 */
function readHeaderMSDOS($handle) {
    $e_magic = fread($handle, 2);
    fseek($handle, PE_START);
    $e_lfanew = readDWord($handle);
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
function readHeaderCOFF($handle) {
    $result = array(
        'signature' => fread($handle, 4), //string
        'machine' => readWord($handle),
        'numberOfSections' => readWord($handle),
        'timeDateStamp' => readDWord($handle),
        'pointerToSymbolTable' => readDWord($handle),
        'numberOfSymbols' => readDWord($handle),
        'sizeOfOptionalHeader' => readWord($handle),
        'characteristics' => readWord($handle),
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
function readHeaderOptional($handle) {
    $magic = readWord($handle);
    $standart = array(
        'magic' => $magic,
        'majorLinkerVersion' => readByte($handle),
        'minorLinkerVersion' => readByte($handle),
        'sizeOfCode' => readDWord($handle),
        'sizeOfInitializedData' => readDWord($handle),
        'sizeOfUninitializedData' => readDWord($handle),
        'addressOfEntryPoint' => readDWord($handle),
        'baseOfCode' => readDWord($handle),
    );
    if($magic == 0x10B) {
        $standart['baseOfData'] = readDWord($handle);
    }
    $addon = array(
        'imageBase' => ($magic == 0x10B ? readDWord($handle) : readQWord($handle)),
        'sectionAligment' => readDWord($handle),
        'fileAligment' => readDWord($handle),
        'majorOperatingSystemVersion' => readWord($handle),
        'minorOperatingSystemVersion' => readWord($handle),
        'majorImageVersion' => readWord($handle),
        'minorImageVersion' => readWord($handle),
        'majorSubsystemVersion' => readWord($handle),
        'minorSubsystemVersion' => readWord($handle),
        'win32VersionValue' => readDWord($handle),
        'sizeOfImage' => readDWord($handle),
        'sizeOfHeaders' => readDWord($handle),
        'checkSum' => readDWord($handle),
        'subsystem' => readWord($handle),
        'dllCharacteristics' => readWord($handle),
        'sizeOfStackReserve' => ($magic == 0x10B ? readDWord($handle) : readQWord($handle)),
        'sizeOfStackCommit' => ($magic == 0x10B ? readDWord($handle) : readQWord($handle)),
        'sizeOfHeapReserve' => ($magic == 0x10B ? readDWord($handle) : readQWord($handle)),
        'sizeOfHeapCommit' => ($magic == 0x10B ? readDWord($handle) : readQWord($handle)),
        'loaderFlags' => readDWord($handle),
        'numberOfRvaAndSizes' => readDWord($handle),
    );
    $result = array_merge($standart, $addon);
    return $result;
}

/**
 * Reads Data Directory entry from PE file.
 * @param resource $handle opened file's descriptor
 * @return array an associative array with Data Directory entry's data
 */
function __readDataDirectory($handle) {
    $result = array(
        'virtualAddress' => readDWord($handle),
        'size' => readDWord($handle),
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
function readTableDataDirectory($handle) {
    $result = array(
        'exportTable' => __readDataDirectory($handle),
        'importTable' => __readDataDirectory($handle),
        'resourceTable' => __readDataDirectory($handle),
        'exceptionTable' => __readDataDirectory($handle),
        'certificateTable' => __readDataDirectory($handle),
        'baseRelocationTable' => __readDataDirectory($handle),
        'debug' => __readDataDirectory($handle),
        'architecture' => __readDataDirectory($handle),
        'globalPtr' => __readDataDirectory($handle),
        'tlsTable' => __readDataDirectory($handle),
        'loadConfigTable' => __readDataDirectory($handle),
        'boundImport' => __readDataDirectory($handle),
        'iAT' => __readDataDirectory($handle),
        'delayImportDescriptor' => __readDataDirectory($handle),
        'clrRuntimeHeader' => __readDataDirectory($handle),
        'reserved' => __readDataDirectory($handle),
    );
    return $result;
}

/**
 * Reads Section entry from PE file.
 * @param resource $handle opened file's descriptor
 * @return array an associative array with Section entry's data
 */
function __readSection($handle) {
    $result = array(
        'name' => rtrim(fread($handle, 8), "\0"), //string
        'virtualSize' => readDWord($handle),
        'virtualAddress' => readDWord($handle),
        'sizeOfRawData' => readDWord($handle),
        'pointerToRawData' => readDWord($handle),
        'pointerToRelocations' => readDWord($handle),
        'pointerToLineNumbers' => readDWord($handle),
        'numberOfRelocations' => readWord($handle),
        'numberOfLineNumbers' => readWord($handle),
        'characteristics' => readDWord($handle),
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
function readTableSection($handle, $numberOfSections) {
    $result = array();
    for($i = 0; $i < $numberOfSections; ++$i) {
        $section = __readSection($handle);
        $result[$section['name']] = $section;
    }
    return $result;
}

/**
 * Reads Thunk(import) entry from PE file.
 * @param resource $handle opened file's descriptor
 * @return array an associative array with Thunk entry's data
 */
function __readThunkEntry($handle) {
    $result = array(
        'originalFirstThunk' => readDWord($handle),
        'timeDateStamp' => readDWord($handle),
        'forwarderChain' => readDWord($handle),
        'name' => readDWord($handle),
        'firstThunk' => readDWord($handle),
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
function readTableImport($handle, $offsetSection, $is64) {
    $result = array();
    $isThunk = true;
    while($isThunk) {
        $thunk = __readThunkEntry($handle);
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
                    $function['addressOfData'] = readQWord($handle);
                } else {
                    $sizeOfAddressOfData = 4;
                    $function['addressOfData'] = readDWord($handle);
                }
                if($function['addressOfData']) {
                    if($function['addressOfData'] & ($is64 ? ORDINAL_MASK_64 :ORDINAL_MASK_32)) {
                        $function['ordinal'] = '#' . ($function['addressOfData'] ^ ($is64 ? ORDINAL_MASK_64 :ORDINAL_MASK_32));
                    } else {
                        $nameOfFunction = '';
                        $symbol = "\0";
                        fseek($handle, $offsetSection + $function['addressOfData']);
                        $function['hint'] = readWord($handle);
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
function __readHeaderResourceDirectory($handle) {
    $result = array(
        'characteristics' => readDWord($handle),
        'timeDateStamp' => readDWord($handle),
        'majorVersion' => readWord($handle),
        'minorVersion' => readWord($handle),
        'numberOfNameEntries' => readWord($handle),
        'numberOfIdEntries' => readWord($handle),
    );
    return $result;
}

/**
 * Reads Resource Directory entry from PE file.
 * @param resource $handle opened file's descriptor
 * @return array an associative array with Resource Directory entry's data
 */
function __readResourceDirectoryEntry($handle) {
    $result = array();
    $first = readDWord($handle);
    if($first & ORDINAL_MASK_32) {
        $result['nameRVA'] = $first ^ ORDINAL_MASK_32;
    } else {
        $result['integerId'] = $first;
    }
    $second = readDWord($handle);
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
function __readResourceDataEntry($handle) {
    $result = array(
        'dataRVA' => readDWord($handle),
        'size' => readDWord($handle),
        'codepage' => readDWord($handle),
        'reserved' => readDWord($handle),
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
function readTreeResource($handle, $offsetBase, $tableResourceVA, $count) {
    $result = array();
    $resourceDH = __readHeaderResourceDirectory($handle);
    for($i = 0; $i < $resourceDH['numberOfNameEntries'] + $resourceDH['numberOfIdEntries']; ++$i) {
        $resourceDE = __readResourceDirectoryEntry($handle);
        $offsetEntry = ftell($handle);
        if(isset($resourceDE['nameRVA'])) {
            fseek($handle, $offsetBase + $resourceDE['nameRVA']);
            $length = readWord($handle);
            $nameOfEntry = '';
            for($j = 0; $j < $length; ++$j) {
                $nameOfEntry .= fread($handle, 1);
                fread($handle, 1); //multibyte encoding
            }
            $result[$i]['name'] = $nameOfEntry;
        } else {
            $result[$i]['ordinal'] = $resourceDE['integerId'];
        }
        $result[$i]['directoryEntry'] = $resourceDE;
        $result[$i]['directoryHeader'] = $resourceDH;
        if(isset($resourceDE['dataEntryRVA'])) {
            fseek($handle, $offsetBase + $resourceDE['dataEntryRVA']);
            $resourceDataEntry = __readResourceDataEntry($handle);
            $offsetData = $offsetBase + $resourceDataEntry['dataRVA'] - $tableResourceVA;
            fseek($handle, $offsetData);
            $data = fread($handle, $resourceDataEntry['size']);
            $resourceDataEntry['data'] = $data;
            $result[$i]['content'] = $resourceDataEntry;
        } else {
            fseek($handle, $offsetBase + $resourceDE['subdirectoryRVA']);
            $result[$i]['content'] = readTreeResource($handle, $offsetBase, $tableResourceVA, $count + 1);
        }
        fseek($handle, $offsetEntry);
    }
    return $result;
}

/**
 * Parses the PE file and dumps the data from it.
 * @param resource $handle handler of the opened PE file
 * @return array an associative array(of associative arrays) with PE file's data
 */
function parsePEFile($handle) {
    $result = array();
    //MSDOS header
    fseek($handle, 0);
    $result['headerMSDOS'] = readHeaderMSDOS($handle);
    //COFF header
    fseek($handle, $result['headerMSDOS']['e_lfanew']);
    $result['headerCOFF'] = readHeaderCOFF($handle);
    //Optional header
    $offsetHeaderOptional = ftell($handle);
    $result['headerOptional'] = readHeaderOptional($handle);
    //Data Directories table
    $result['tableDataDirectory'] = readTableDataDirectory($handle);
    //Sections
    $offsetTableSection = $offsetHeaderOptional + $result['headerCOFF']['sizeOfOptionalHeader'];
    fseek($handle, $offsetTableSection);
    $result['tableSection'] = readTableSection($handle, $result['headerCOFF']['numberOfSections']);
    //Import table
    $tableImportVA = $result['tableDataDirectory']['importTable']['virtualAddress'];
    if($tableImportVA) {
        $offsetTableImport = 0;
        $offsetSectionImport = 0;
        foreach($result['tableSection'] as $section) {
            if($section['virtualAddress'] <= $tableImportVA && 
               $tableImportVA < $section['virtualAddress'] + $section['sizeOfRawData']) {
                $offsetTableImport = $section['pointerToRawData'] + $tableImportVA - $section['virtualAddress'];
                $offsetSectionImport = $section['pointerToRawData'] - $section['virtualAddress'];
                break;
            }
        }
        fseek($handle, $offsetTableImport);
        $is64 = $result['headerOptional']['magic'] == 0x10B ? false : true;
        $result['tableImport'] = readTableImport($handle, $offsetSectionImport, $is64);
    }
    //Resource tree
    $treeResourceVA = $result['tableDataDirectory']['resourceTable']['virtualAddress'];
    if($treeResourceVA) {
        $offsetTreeResource = 0;
        foreach($result['tableSection'] as $section) {
            if($section['virtualAddress'] <= $treeResourceVA && 
               $treeResourceVA < $section['virtualAddress'] + $section['sizeOfRawData']) {
                $offsetTreeResource = $section['pointerToRawData'] + $treeResourceVA - $section['virtualAddress'];
                break;
            }
        }
        fseek($handle, $offsetTreeResource);
        $result['treeResource'] = readTreeResource($handle, $offsetTreeResource, $treeResourceVA, 0);
    }
                
    return $result;
}

?>