<?php

/**
 * 生成二维码
 */
include FCPATH.DIRECTORY_SEPARATOR.'application/third_party/phpqrcode/qrlib.php';
class QrUtil {
    static function generate_qrcode($str) {
        $PNG_TEMP_DIR = FCPATH.'public/upload/phpqrcode/temp/';
        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR)) {
            mkdir($PNG_TEMP_DIR);
        }
        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 4;
        
        $filename = md5($str.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        $file_path = $PNG_TEMP_DIR.$filename;
        QRcode::png($str, $file_path, $errorCorrectionLevel, $matrixPointSize, 2);
        
        return 'public/upload/phpqrcode/temp/'.$filename;
    }
}