<?php

define('SIZE_OF_BYTE', 1);
define('SIZE_OF_WORD', 2);
define('SIZE_OF_DWORD', 4);
define('SIZE_OF_QWORD', 8);
define('SIZE_OF_KILOBYTE', 1024);
define('SIZE_OF_MEGABYTE', 1048576);
define('SIZE_OF_GIGABYTE', 1073741824);

define('PE_START', 0x3C);
define('ORDINAL_MASK_32', 0x80000000);
define('ORDINAL_MASK_64', 0x8000000000000000);

$GLOBALS['coffMachine'] = array(
    0x0 => 'Not specified',
    0x1d3 => 'Matsushita AM33',
    0x8664 => 'x64',
    0x1c0 => 'ARM little endian',
    0x1c4 => 'ARMv7 (or higher) Thumb mode only',
    0xaa64 => 'ARMv8 in 64-bit mode',
    0xebc => 'EFI byte code',
    0x14c => 'Intel 386 or later processors and compatible processors',
    0x200 => 'Intel Itanium processor family',
    0x9041 => 'Mitsubishi M32R little endian',
    0x266 => 'MIPS16',
    0x366 => 'MIPS with FPU',
    0x466 => 'MIPS16 with FPU',
    0x1f0 => 'Power PC little endian',
    0x1f1 => 'Power PC with floating point support',
    0x166 => 'MIPS little endian',
    0x1a2 => 'Hitachi SH3',
    0x1a3 => 'Hitachi SH3 DSP',
    0x1a6 => 'Hitachi SH4',
    0x1a8 => 'Hitachi SH5',
    0x1c2 => 'ARM or Thumb (“interworking”)',
    0x169 => 'MIPS little-endian WCE v2',
);

$GLOBALS['coffCharacteristics'] = array(
    0x0001 => 'RELOCS_STRIPPED',
    0x0002 => 'EXECUTABLE_IMAGE',
    0x0004 => 'LINE_NUMS_STRIPPED',
    0x0008 => 'LOCAL_SYMS_STRIPPED',
    0x0010 => 'AGGRESSIVE_WS_TRIM',
    0x0020 => 'LARGE_ADDRESS_ AWARE',
    0x0040 => 'RESERVED',
    0x0080 => 'BYTES_REVERSED_LO',
    0x0100 => '32BIT_MACHINE',
    0x0200 => 'DEBUG_STRIPPED',
    0x0400 => 'REMOVABLE_RUN_ FROM_SWAP',
    0x0800 => 'NET_RUN_FROM_SWAP',
    0x1000 => 'SYSTEM',
    0x2000 => 'DLL',
    0x4000 => 'UP_SYSTEM_ONLY',
    0x8000 => 'BYTES_REVERSED_HI',
);

$GLOBALS['optionalMagic'] = array(
    0x10b => 'PE32',
    0x20b => 'PE32+',
);

$GLOBALS['optionalSubsystem'] = array(
    0 => 'UNKNOWN',
    1 => 'NATIVE',
    2 => 'WINDOWS_GUI',
    3 => 'WINDOWS_CUI',
    7 => 'POSIX_CUI',
    9 => 'WINDOWS_CE_GUI',
    10 => 'EFI_APPLICATION',
    11 => 'EFI_BOOT_ SERVICE_DRIVER',
    12 => 'EFI_RUNTIME_DRIVER',
    13 => 'EFI_ROM',
    14 => 'XBOX',
);

$GLOBALS['optionalDllCharacteristics'] = array(
    0x0001 => 'RESERVED',
    0x0002 => 'RESERVED',
    0x0004 => 'RESERVED',
    0x0008 => 'RESERVED',
    0x0040 => 'DYNAMIC_BASE',
    0x0080 => 'FORCE_INTEGRITY',
    0x0100 => 'NX_COMPAT',
    0x0200 => ' NO_ISOLATION',
    0x0400 => ' NO_SEH',
    0x0800 => ' NO_BIND',
    0x1000 => 'RESERVED',
    0x2000 => ' WDM_DRIVER',
    0x8000 => ' TERMINAL_SERVER_AWARE',
);

$GLOBALS['sectionCharacteristics'] = array(
    0x00000000 => 'RESERVED',
    0x00000001 => 'RESERVED',
    0x00000002 => 'RESERVED',
    0x00000004 => 'RESERVED',
    0x00000008 => 'TYPE_NO_PAD',
    0x00000010 => 'RESERVED',
    0x00000020 => 'CNT_CODE',
    0x00000040 => 'CNT_INITIALIZED_DATA',
    0x00000080 => 'CNT_UNINITIALIZED_DATA',
    0x00000100 => 'LNK_OTHER',
    0x00000200 => 'LNK_INFO',
    0x00000400 => 'RESERVED',
    0x00000800 => 'LNK_REMOVE',
    0x00001000 => 'LNK_COMDAT',
    0x00008000 => 'GPREL',
    0x00020000 => 'MEM_PURGEABLE',
    0x00020000 => 'MEM_16BIT',
    0x00040000 => 'MEM_LOCKED',
    0x00080000 => 'MEM_PRELOAD',
    0x00100000 => 'ALIGN_1BYTES',
    0x00200000 => 'ALIGN_2BYTES',
    0x00300000 => 'ALIGN_4BYTES',
    0x00400000 => 'ALIGN_8BYTES',
    0x00500000 => 'ALIGN_16BYTES',
    0x00600000 => 'ALIGN_32BYTES',
    0x00700000 => 'ALIGN_64BYTES',
    0x00800000 => 'ALIGN_128BYTES',
    0x00900000 => 'ALIGN_256BYTES',
    0x00A00000 => 'ALIGN_512BYTES',
    0x00B00000 => 'ALIGN_1024BYTES',
    0x00C00000 => 'ALIGN_2048BYTES',
    0x00D00000 => 'ALIGN_4096BYTES',
    0x00E00000 => 'ALIGN_8192BYTES',
    0x01000000 => 'LNK_NRELOC_OVFL',
    0x02000000 => 'MEM_DISCARDABLE',
    0x04000000 => 'MEM_NOT_CACHED',
    0x08000000 => 'MEM_NOT_PAGED',
    0x10000000 => 'MEM_SHARED',
    0x20000000 => 'MEM_EXECUTE',
    0x40000000 => 'MEM_READ',
    0x80000000 => 'MEM_WRITE',
);
?>
