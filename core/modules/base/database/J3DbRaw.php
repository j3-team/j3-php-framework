<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3Raw.php
 *
 * This file contains the class for RAW data to be used in SQL queries/statements.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-09: Initial version
 *  2. 2017-07-15: Documentation
 */

namespace J3\Core\Modules\Base\Database;

class J3DbRaw {
   public $raw;

   public function __construct($value) {
      $this->raw = $value;
   }
}


?>
