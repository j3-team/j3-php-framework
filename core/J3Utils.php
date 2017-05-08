<?php
/**
 * J3 PHP Framework - core/Utils.php
 *
 * This file contains utiles functions.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-03-14: Initial version
 *  2. 2017-05-06: Rename class
 */

namespace J3\Core;

class J3Utils {

   static function downloadFile($file, $type) {
      if (file_exists($file)) {
   		header('Content-Description: File Transfer');
   		header('Content-Type: ' . $type);
   		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
   		header('Expires: 0');
   		header('Cache-Control: must-revalidate');
   		header('Pragma: public');
   		header('Content-Length: ' . filesize($file));
   		readfile($file);
   	}
   	exit;
   }


}

?>
