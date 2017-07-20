<?php
/**
 * J3 PHP Framework - index.php
 *
 * This file contains the index execution for framework.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-03-13: Initial version
 *  2. 2017-07-20: Replace "strings" for 'strings'
 */

namespace J3;

require_once 'core/J3Core.php';

use J3\Core\J3Core;
use J3\Core\J3Utils;

/* Validate parameters */

// 1. pController doesn't exist
if (!isset($_REQUEST['pController']) || $_REQUEST['pController'] == '') {
   J3Core::welcome();
   exit(0);
}

// 2. pController exists
$c = $_REQUEST['pController'];

if ($c == 'sitemap.xml') {
   J3Core::sitemap();
}

if ($c == 'robots.txt') {
   J3Core::robots();
}

if ($c == 'phpinfo') {
   J3Core::phpinfo();
}

// 3. Continue load
$m = null;
$o = null;

if (isset($_REQUEST['pMethod'])) {
   $m = $_REQUEST['pMethod'];
}

if (isset($_REQUEST['pOthers'])) {
   $o = $_REQUEST['pOthers'];
}

J3Core::processRequest($c, $m, $o);

?>
