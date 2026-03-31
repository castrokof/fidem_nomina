<?php
// config/dompdf.php

return [
    'show_warnings' => false,
    'orientation' => 'portrait',
    
    'defines' => [
        'font_dir' => storage_path('fonts'),
        'font_cache' => storage_path('fonts'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        
        // ✅ FUENTE COMPATIBLE CON UTF-8
        'default_font' => 'sans-serif',
        
        'dpi' => 96,
        'enable_php' => true,
        'enable_javascript' => false,  // ← Desactivar JS para seguridad
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        
        // ✅ SOPORTE UNICODE CRÍTICO
        'DOMPDF_UNICODE_ENABLED' => true,
        'DOMPDF_CHARSET' => 'UTF-8',
        'DOMPDF_ENABLE_HTML5_PARSER' => true,
        
        'log_output_file' => null,
    ],
];