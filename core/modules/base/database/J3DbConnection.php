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

   public function __construct($id) {
      // TODO look for $id within db.ini file
   }

   public function isConnected() {
      return isset($dbconnection);
   }

   /* ABSTRACT METHODS */

   public abstract function connect();

   public abstract function disconnect();

   public abstract function getLastError();

   public abstract function initTransaction();

   public abstract function commit();

   public abstract function rollback();

   public abstract function execQuery($sql);

   public abstract function runCallable($sentence, $params);

   public abstract function prepare($sql);


}

?>
