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
 *  3. 2017-07-15: Renamed methods
 *                 Documentation
 */

namespace J3\Core\Modules\Base\Database;

require_once 'J3DbCondition.php';

use J3\Core\J3Utils;

class J3DbModel {

   /* Constans */
   const SQL_SELECT     = 'SELECT';
   const SQL_UPDATE     = 'UPDATE';
   const SQL_DELETE     = 'DELETE';
   const SQL_INSERT     = 'INSERT INTO';
   const SQL_VALUES     = 'VALUES';
   const SQL_FROM       = 'FROM';
   const SQL_WHERE      = 'WHERE';
   const SQL_ORDERBY    = 'ORDER BY';
   const SQL_GROUPBY    = 'GROUP BY';
   const SQL_HAVING     = 'HAVING';

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

   /**
    * Starts one SELECT query.
    * @param  array  $fields Fields to select. '*' if doesn't set
    * @return J3DbModel      This object
    */
   public function _select($fields = array()) {
      $this->isSelect = true;

      if (!is_array($fields)) {
         $f = $fields;
      } else if (!empty($fields)) {
         $f = implode(', ', $fields);
      } else {
         $f = '*';
      }
      $this->sql = J3DbModel::SQL_SELECT . " $f\n  " . J3DbModel::SQL_FROM . " $this->table";

      return $this;
   }

   public function _where($field = null, $operator = null, $params = null) {
      $this->condition = new J3DbCondition($field, $operator, $params);
      return $this;
   }

   public function _and($field, $operator = null, $params = null) {
      if (!isset($this->condition)) {
         return $this->_where($field, $operator, $params);
      } else {
         $this->condition->_and($field, $operator, $params);
         return $this;
      }
   }

   public function _or($field, $operator = null, $params = null) {
      if (!isset($this->condition)) {
         return $this->_where($field, $operator, $params);
      } else {
         $this->condition->_or($field, $operator, $params);
         return $this;
      }
   }

   public function _do() {
      // TODO
   }

   public function _insert($values = array()) {
      $this->isSelect = false;

      $arrf = array();
      $arrv = array();
      foreach ($values as $key => $value) {
         array_push($arrf, $key);
         array_push($arrv, J3DbCondition::parseValue($value));
      }

      $this->sql = J3DbModel::SQL_INSERT . " $this->table (" . implode(', ', $arrf) .") " . J3DbModel::SQL_VALUES . " (" . implode(', ', $arrv) . ")";

      return $this;
   }

   public function _update($sets = array()) {
      $this->isSelect = false;

      $arr = array();
      foreach ($sets as $key => $value) {
         array_push($arr, "$key = " . J3DbCondition::parseValue($value));
      }

      $this->sql = J3DbModel::SQL_UPDATE . " $this->table \nSET " . implode(', ', $arr);

      return $this;
   }

   public function _delete() {
      $this->sql = J3DbModel::SQL_DELETE . " $this->table";
      return $this;
   }

   public function _orderBy($field, $order = null) {
      if (!isset($this->orderBy)) {
         $this->orderBy = array();
      }

      if (!isset($order)) {
         $order = 'ASC';
      }

      array_push($this->orderBy, "$field $order");

      return $this;
   }

   public function _groupBy($field) {
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

   public function _having($what) {
      $this->having = J3DbModel::SQL_HAVING . " $what";

      return $this;
   }

   private function build() {
      $grp = isset($this->groupBy) ? "\n" . J3DbModel::SQL_GROUPBY . " " . implode(', ',$this->groupBy) : "";
      $ord = isset($this->orderBy) ? "\n" . J3DbModel::SQL_ORDERBY . " " . implode(', ',$this->orderBy) : "";
      $hav = isset($this->having) ? "\n$this->having" : "";
      $con = isset($this->condition) ? "\n " . J3DbModel::SQL_WHERE . " ".$this->condition->sql : "";

      return "$this->sql $con $grp $hav $ord";
   }

   public function sql() {
      return $this->build();
   }

}

?>
