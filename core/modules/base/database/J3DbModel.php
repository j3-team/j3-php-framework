<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DbModel.php
 *
 * This file contains the base class for Database Models
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-08: Initial version
 *  2. 2017-07-09: Initial version continue...
 */

namespace J3\Core\Modules\Base\Database;

require_once 'J3DbCondition.php';

use J3\Core\J3Utils;

class J3DbModel {

   /* Constans */
   const SQL_SELECT     = 'SELECT';
   const SQL_FROM       = 'FROM';
   const SQL_WHERE      = 'WHERE';
   const SQL_AND        = 'AND';
   const SQL_OR         = 'OR';
   const SQL_ORDERBY    = 'ORDER BY';
   const SQL_GROUPBY    = 'GROUP BY';

   /* Attributes */
   private $connection;
   private $table;
   private $pk;

   private $sql;
   private $isSelect = true;
   private $condition;
   private $orderBy;
   private $having;

   public function __construct($alias = null, $table = null) {
      // get class annotations
      $this->classAnnotations = J3Utils::getClassAnnotations($this);

      //evaluate class annotations
      foreach ($this->classAnnotations as $key => $value) {
         $withValue = true;
         if ($key === J3Utils::ANN_DBMODEL_TABLE) {
            $this->table = $value;
         } else if ($key === J3Utils::ANN_DBMODEL_PK) {
            $this->pk = $value;
         }

         if ($withValue === true && ($value === "" || !isset($value) || $value === null)) {
            J3View::warning("Annotation <strong>$key</strong> must have a value.");
         }
      }

      // Table Name
      if (isset($table)) {
         $this->table = $table;
      }

      if (!isset($this->table)) {
         $this->table = strtolower(get_class($this));
      }

      if (isset($alias)) {
         $this->table = $this->table . " $alias";
      }
   }

   public function select($fields = array()) {
      $this->isSelect = true;

      if (!is_array($fields)) {
         $f = $fields;
      } else if (!empty($fields)) {
         $f = implode(', ', $fields);
      } else {
         $f = '*';
      }
      $this->sql = "SELECT $f FROM $this->table";

      return $this;
   }

   public function where($field = null, $operator = null, $params = null) {
      $this->condition = new J3DbCondition($field, $operator, $params);
      return $this;
   }

   public function _and($field, $operator = null, $params = null) {
      if (!isset($this->condition)) {
         return $this->where($field, $operator, $params);
      } else {
         $this->condition->_and($field, $operator, $params);
         return $this;
      }
   }

   public function _or($field, $operator = null, $params = null) {
      if (!isset($this->condition)) {
         return $this->where($field, $operator, $params);
      } else {
         $this->condition->_or($field, $operator, $params);
         return $this;
      }
   }

   public function _do() {
      // TODO
   }

   public function insert($values = array()) {
      $this->isSelect = false;

      $arrf = array();
      $arrv = array();
      foreach ($values as $key => $value) {
         array_push($arrf, $key);
         array_push($arrv, J3DbCondition::parseValue($value));
      }

      $this->sql = "INSERT INTO $this->table (" . implode(', ', $arrf) .") VALUES (" . implode(', ', $arrv) . ")";

      return $this;
   }

   public function update($sets = array()) {
      $this->isSelect = false;

      $arr = array();
      foreach ($sets as $key => $value) {
         array_push($arr, "$key = " . J3DbCondition::parseValue($value));
      }

      $this->sql = "UPDATE $this->table \nSET " . implode(', ', $arr);

      return $this;
   }

   public function delete() {
      $this->sql = "DELETE $this->table";
      return $this;
   }

   public function orderBy($field, $order = null) {
      if (!isset($this->orderBy)) {
         $this->orderBy = array();
      }

      if (!isset($order)) {
         $order = 'ASC';
      }

      array_push($this->orderBy, "$field $order");

      return $this;
   }

   public function groupBy($field) {
      if (!isset($this->groupBy)) {
         $this->groupBy = array();
      }

      if (!is_array($field)) {
         array_push($this->groupBy, $field);
      } else {
         foreach ($field as $key => $value) {
            array_push($this->groupBy, $value);
         }
      }

      return $this;
   }

   public function having($what) {
      $this->having = "HAVING $what";

      return $this;
   }

   private function build() {
      $grp = isset($this->groupBy) ? "\nGROUP BY " . implode(', ',$this->groupBy) : "";
      $ord = isset($this->orderBy) ? "\nORDER BY " . implode(', ',$this->orderBy) : "";
      $hav = isset($this->having) ? "\n$this->having" : "";
      $con = isset($this->condition) ? "\n WHERE ".$this->condition->sql : "";

      return "$this->sql $con $grp $hav $ord";
   }

   public function sql() {
      return $this->build();
   }

}

?>
