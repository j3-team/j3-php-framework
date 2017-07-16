<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DbConnection.php
 *
 * This file contains the base class for Database Connections
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-08: Initial version
 *  2. 2017-07-15: Documentation
  */

namespace J3\Core\Modules\Base\Database;

use J3\Core\J3Utils;

class J3DbConnection {
   private $id; // section identification (db.ini file
   private $dbconnection;
   private $host;
   private $port;
   private $schema;
   private $database;
   private $username;
   private $password;
   private $persistence;
   private $error;

   /**
    * Init connection values from db.ini file.
    * @param string $id Connection name.
    */
   public function __construct($id) {
      // TODO look for $id within db.ini file
   }

   /**
    * Returns if connection with database is alive.
    * @return boolean Connected to database
    */
   public function connected() {
      return isset($dbconnection);
   }

   /**
    * Returns last error on DB operation.
    * @return array Database operation error in array( 0 => code, 1 => 'desc' )
    */
   public function error() {
      return $this->error;
   }

   /* ABSTRACT METHODS */

   /**
    * Try Connect to database
    * @return boolean Connection successful
    */
   public abstract function connect();

   /**
    * Try Disconnect from database
    * @return boolean Disconnection successful
    */
   public abstract function disconnect();

   /**
    * Returns last error on database process
    * @return string
    */
   public abstract function lastError();

   /**
    * Starts one global database transaction.
    * @return boolean
    */
   public abstract function initTransaction();

   /**
    * Commits current transaction to database.
    * @return boolean
    */
   public abstract function commit();

   /**
    * Rollbacks current transaction to database.
    * @return boolean
    */
   public abstract function rollback();

   /**
    * Executes given sql text into database.
    * @return boolean
    */
   public abstract function execQuery($sql);

   /**
    * Executes given stored procedure with given parameters
    * @return boolean
    */
   public abstract function call($sentence, $params);

   /**
    * Prepares next sql query/statement.
    * @return boolean
    */
   public abstract function prepare($sql);

   /**
    * Executes previous prepare query/statement.
    * @return boolean
    */
   public abstract function exec($params = array());
}

?>
