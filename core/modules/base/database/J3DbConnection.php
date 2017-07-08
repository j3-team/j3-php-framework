<?php
/**
 * J3 PHP Framework - core/modules/base/database/J3DbConnection.php
 *
 * This file contains the base code for Database Connections
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-08: Initial version
  */

namespace J3\Core\Modules\Base\Database;

use J3\Core\J3Utils;

abstract class J3DbConnection {
   private $host;
   private $port;
   private $schema;
   private $database;
   private $username;
   private $password;

   public function __construct() {
      
   }

}

?>
