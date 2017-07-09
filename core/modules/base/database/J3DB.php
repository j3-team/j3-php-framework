<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DB.php
 *
 * This file contains the base class for manage multiple db connections.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-08: Initial version
  */

namespace J3\Core\Modules\Base\Database;

require_once "J3DbRaw.php";
require_once "J3DbCondition.php";

class J3DB {

   private static $connections = array();

   public static function load() {

   }

   public static function cond($field, $operator = null, $value = null) {
      return new J3DbCondition($field, $operator, $value);
   }

   public static function raw($text) {
      return new J3DbRaw($text);
   }

   public static function table($name, $alias = '') {
      return new J3DbModel($alias, $name);
   }
}

?>
