<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DB.php
 *
 * This file contains the class for manage multiple db connections and offer
 * static functions in order to get easier the development.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-08: Initial version
 *  2. 2017-07-15: Documentation
 */

namespace J3\Core\Modules\Base\Database;

require_once "J3DbRaw.php";
require_once "J3DbCondition.php";

class J3DB {

   private static $connections = array();

   /**
    * Load all database settings in db.ini file.
    * @return void
    */
   public static function load() {
      //TODO load databases from db.ini file
   }

   /**
    * Changes default database in use (Global scope).
    * @param  string $database Connection name
    * @return void
    */
   public static function useDB($database) {
      //TODO change default database in use
   }

   /**
    * Creates and return a new DB Condition Object.
    * @param  Mixed   $field    Condition field / Another J3DbCondition object
    * @param  String $operator  Operator / value
    * @param  Mixed  $value     value of condition (String, number, array, J3DbModel)
    * @return J3DbCondition      DB Condition Object
    */
   public static function cond($field, $operator = null, $value = null) {
      return new J3DbCondition($field, $operator, $value);
   }

   /**
    * Parse the text and return it in form of Raw Object, for SQL statements.
    * @param  String $text Raw String.
    * @return J3DbRaw       DB RAW Object.
    */
   public static function raw($text) {
      return new J3DbRaw($text);
   }

   /**
    * Creates and return one Db Model Object with given table and alias.
    * @param  string $name  Table name.
    * @param  string $alias Alias name (Optional)
    * @return J3DbModel     Db Model Object
    */
   public static function table($name, $alias = '') {
      return new J3DbModel($alias, $name);
   }
}

?>
