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
 *  3. 2017-07-16: Constructor implementation
 */

namespace J3\Core\Modules\Base\Database;

use J3\Core\J3Utils;

abstract class J3DbConnection {
   protected $dbconnection;
   protected $host;
   protected $port;
   protected $schema;
   protected $username;
   protected $password;
   protected $persistence;
   protected $error;

   /**
    * Init connection values.
    * @param string  $host        Database hostname / IP
    * @param integer $port        Database port number
    * @param string  $schema      Database or schema name
    * @param string  $user        User name
    * @param string  $pass        Pasword
    * @param boolean $persistence Make database connection with persistence (if is possible)
    */
   public function __construct($host, $port, $schema, $user, $pass, $persistence = false) {
      $this->host = $host;
      $this->port = $port;
      $this->schema = $schema;
      $this->username = $user;
      $this->password = $pass;
      $this->persistence = $persistence;
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
