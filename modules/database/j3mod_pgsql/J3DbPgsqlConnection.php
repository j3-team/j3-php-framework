<?php
/**
 * J3 PHP Framework - modules/database/J3DbPgsqlConnection.php
 *
 * This file contains the class for PostgreSQL Database Connections
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-18: Initial version
 */

namespace J3\Modules\Database\Pgsql;

use J3\Core\Modules\Base\Database\J3DbConnection;

class J3DbPgsqlConnection extends J3DbConnection {

   /**
    * Try Connect to database
    * @return boolean Connection successful
    */
   public function connect() {
      if ( $this->persistence == 0 ) {
			$this->dbconnection = pg_connect("host=$this->host port=$this->port user=$this->username password=$this->password dbname=$this->schema");
		} else {
			$this->dbconnection = pg_pconnect("host=$this->host port=$this->port user=$this->username password=$this->password dbname=$this->schema");
		}

      if (!$this->dbconnection)	{
			return false;
		}

      return true;
   }

   /**
    * Try Disconnect from database
    * @return boolean Disconnection successful
    */
   public function disconnect() {
      $this->dbconnection = pg_close($this->dbconnection);
   }

   /**
    * Returns last error on database process
    * @return string
    */
   public function lastError() {
      return pg_last_error($this->dbconnection);
   }

   /**
    * Starts one global database transaction.
    * @return boolean
    */
   public function initTransaction() {
      return pg_query($this->dbconnection, 'BEGIN');
   }

   /**
    * Commits current transaction to database.
    * @return boolean
    */
   public function commit() {
      return pg_query($this->dbconnection, 'COMMIT');
   }

   /**
    * Rollbacks current transaction to database.
    * @return boolean
    */
   public function rollback() {
      return pg_query($this->dbconnection, 'COMMIT');
   }

   /**
    * Executes given sql text into database.
    * @return boolean
    */
   public function execQuery($sql) {
      return pg_query($this->dbconnection, $sql);
   }

   /**
    * Executes given stored procedure with given parameters
    * @return boolean
    */
   public function call($sentence, $params) {

   }

   /**
    * Prepares next sql query/statement.
    * @return boolean
    */
   public function prepare($sql) {

   }

   /**
    * Executes previous prepare query/statement.
    * @return boolean
    */
   public function exec($params = array()) {

   }
}

?>
