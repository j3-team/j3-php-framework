<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DbCondition.php
 *
 * This file contains the base class for query conditions builder.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-09: Initial version
  */

namespace J3\Core\Modules\Base\Database;

class J3DbCondition {
   public $sql;

   public function __construct($field = null, $operator = null, $value = null) {
      $this->sql = $this->verifyAndBuild($field, $operator, $value);
   }

   /**
    * Builds query conditions
    * @param  Mixed   $field    Condition field / Another J3DbCondition object
    * @param  String $operator  Operator / value
    * @param  Mixed  $value     value of condition (String, number, array, J3DbModel)
    * @return String            Query condition built.
    */
   private function build($field, $operator, $value) {
      if (isset($value)) {
         $sql = "$field $operator ";
         $sql = $sql . J3DbCondition::parseValue($value);
      } else if (get_class($field) === 'J3\Core\Modules\Base\Database\J3DbCondition') { // Object J3DbCondition (Grouped conditions)
         $sql = '('. $field->sql .')';
      }

      return $sql;
   }

   /**
    * Parse value in order to be ok for SQL
    * @param  String $value Original value
    * @return String        Parsed value
    */
   public static function parseValue($value) {
      if (is_string($value)) {
         return "'$value'";
      } else if (is_numeric($value)) {
         return "$value";
      } else if (is_array($value)) {
         foreach ($value as $key => $val) {
            if (is_string($val)) {
               $value[$key] = "'$val'";
            }
         }
         return '(' . implode(',', $value) . ')';
      } else if (get_class($value) === 'J3\Core\Modules\Base\Database\J3DbModel') { // Object J3DbModel (Sub-queries)
         return "(\n" . str_replace("\n", "\n   ", "   ".$value->sql()) . "\n   )";
      } else if (get_class($value) === 'J3\Core\Modules\Base\Database\J3DbRaw') { // RAW data (functions and others)
         return "$value->raw";
      } else {
         throw new Exception("J3DbCondition.parseValue: Value type is not recognized (". gettype($value) .")", 1);
      }
   }

   /**
    * Verify parameters and calls build method
    * @param  Mixed   $field    Condition field / Another J3DbCondition object
    * @param  String $operator  Operator / value
    * @param  Mixed  $value     value of condition (String, number, array, J3DbModel)
    * @return String            Query condition built.
    */
   private function verifyAndBuild($field, $operator, $value) {
      if (!isset($field)) {
         return $this->build('1', '=', 1);
      } else if (!isset($value)) {
         return $this->build($field, '=', $operator);
      } else { // three params
         return $this->build($field, $operator, $value);
      }
   }

   /**
    * Appends new conditions with AND relation operator
    * @param  Mixed   $field    Condition field / Another J3DbCondition object
    * @param  String $operator  Operator / value
    * @param  Mixed  $value     value of condition (String, number, array, J3DbModel)
    * @return J3DbCondition     This object
    */
   public function _and($field, $operator = null, $value = null) {
      if (!isset($operator)) {
            $this->sql = "($this->sql)";
      }
      $this->sql = $this->sql . "\n   AND " . $this->verifyAndBuild($field, $operator, $value);
      return $this;
   }

   /**
   * Appends new conditions with OR relation operator
   * @param  Mixed   $field    Condition field / Another J3DbCondition object
   * @param  String $operator  Operator / value
   * @param  Mixed  $value     value of condition (String, number, array, J3DbModel)
   * @return J3DbCondition     This object
    */
   public function _or($field, $operator = null, $value = null) {
      $this->sql = "($this->sql)" . "\n    OR " . $this->verifyAndBuild($field, $operator, $value);
      return $this;
   }


}

?>
