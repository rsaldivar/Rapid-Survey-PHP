<?php

class Forcedownload {

    function Forcedownload($file, $name, $mime_type = '') {
        /*
          This function takes a path to a file to output ($file),
          the filename that the browser will see ($name) and
          the MIME type of the file ($mime_type, optional).

          If you want to do something on download abort/finish,
          register_shutdown_function('function_name');
         */
        if (!is_readable($file))
            die('File not found or inaccessible!');

        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type (if not specified) */
        /*
          $known_mime_types = array(
          "pdf" => "application/pdf",
          "txt" => "text/plain",
          "html" => "text/html",
          "htm" => "text/html",
          "exe" => "application/octet-stream",
          "zip" => "application/zip",
          "doc" => "application/msword",
          "xls" => "application/vnd.ms-excel",
          "ppt" => "application/vnd.ms-powerpoint",
          "gif" => "image/gif",
          "png" => "image/png",
          "jpeg" => "image/jpg",
          "jpg" => "image/jpg",
          "php" => "text/plain"
          );
         */
        $known_mime_types = array();
        $known_mime_types['ai'] = 'application/postscript';
        $known_mime_types['asx'] = 'video/x-ms-asf';
        $known_mime_types['au'] = 'audio/basic';
        $known_mime_types['avi'] = 'video/x-msvideo';
        $known_mime_types['bmp'] = 'image/bmp';
        $known_mime_types['css'] = 'text/css';
        $known_mime_types['doc'] = 'application/msword';
        $known_mime_types['eps'] = 'application/postscript';
        $known_mime_types['exe'] = 'application/octet-stream';
        $known_mime_types['gif'] = 'image/gif';
        $known_mime_types['htm'] = 'text/html';
        $known_mime_types['html'] = 'text/html';
        $known_mime_types['ico'] = 'image/x-icon';
        $known_mime_types['jpe'] = 'image/jpeg';
        $known_mime_types['jpeg'] = 'image/jpeg';
        $known_mime_types['jpg'] = 'image/jpeg';
        $known_mime_types['js'] = 'application/x-javascript';
        $known_mime_types['mid'] = 'audio/mid';
        $known_mime_types['mov'] = 'video/quicktime';
        $known_mime_types['mp3'] = 'audio/mpeg';
        $known_mime_types['mpeg'] = 'video/mpeg';
        $known_mime_types['mpg'] = 'video/mpeg';
        $known_mime_types['pdf'] = 'application/pdf';
        $known_mime_types['pps'] = 'application/vnd.ms-powerpoint';
        $known_mime_types['ppt'] = 'application/vnd.ms-powerpoint';
        $known_mime_types['ps'] = 'application/postscript';
        $known_mime_types['pub'] = 'application/x-mspublisher';
        $known_mime_types['qt'] = 'video/quicktime';
        $known_mime_types['rtf'] = 'application/rtf';
        $known_mime_types['svg'] = 'image/svg+xml';
        $known_mime_types['swf'] = 'application/x-shockwave-flash';
        $known_mime_types['tif'] = 'image/tiff';
        $known_mime_types['tiff'] = 'image/tiff';
        $known_mime_types['txt'] = 'text/plain';
        $known_mime_types['wav'] = 'audio/x-wav';
        $known_mime_types['wmf'] = 'application/x-msmetafile';
        $known_mime_types['xls'] = 'application/vnd.ms-excel';
        $known_mime_types['zip'] = 'application/zip';
        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            };
        };

        @ob_end_clean(); //turn off output buffering to decrease cpu usage
// required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the 
          download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }

            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        /* output the file itself */
        $chunksize = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else
            die('Error - can not open file.');

        die();
    }

}