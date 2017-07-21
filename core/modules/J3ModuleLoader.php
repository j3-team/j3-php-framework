<?php
/**
 * J3 PHP Framework - core/modules/J3ModuleLoader.php
 *
 * This file contains the main code for load modules
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-03: Initial version
 *  2. 2017-07-20: Load Modules
 */

namespace J3\Core\Modules;

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;

class J3ModuleLoader {

   public static $mod_ini;

   /**
    * Loads external library from "extra" folder.
    * @param  String $name PHP File name (without extension).
    * @return void
    */
   static function loadExtra($name) {
      if (file_exists(J3Utils::DIR_EXTRAS . $name . '.php')) {
         require_once(J3Utils::DIR_EXTRAS . $name . '.php');
      } else {
         J3View::warning("Extra <strong>$name.php</strong> not found.");
      }
   }

   /**
    * Loads one module and returns its config.
    * @param  string $type Module type
    * @param  string $mod  Module name
    * @return array        Module settings
    */
   static function loadModule($type, $mod) {
      // verify enabled
      if (!J3ModuleLoader::is_mod_allowed($type, $mod)) {
         return false;
      }

      // get module path
      $path = J3Utils::DIR_MODULES . $type . '/' . $mod;

      // verify path
      if (!file_exists($path)) {
         J3View::warning("Module <strong>$type/$mod</strong> not found.");
         return false;
      }

      // verify file
      if (!file_exists($path . '/' . J3Utils::FILE_INI_MOD)) {
         J3View::warning("File <strong>mod.ini</strong> for module <strong>$type/$mod</strong> not found.");
         return false;
      }

      // load module settings
      $ini_array = parse_ini_file($path . '/' . J3Utils::FILE_INI_MOD, true);

      // check ini file keys...
      if (!J3ModuleLoader::check_ini_keys($ini_array[J3Utils::MOD_INI_SECTION_MODULE], strtoupper($type))) {
         return false;
      }

      // require_once for file keys
      foreach ($$ini_array[J3Utils::MOD_INI_SECTION_MODULE] as $key => $value) {
         if (strpos($key, '_file') > 0) {
            if (file_exists("$path/$value")) {
               require_once "$path/$value";
            } else {
               J3View::warning("File <strong>$path/$value</strong> not found.");
               return false;
               // TODO evaluate if use exit instead warning
            }
         }
      }

      // process specific settings for every module type
      $method = "load_mod_$type";
      return J3ModuleLoader::$method($ini_array);
   }


   /**
    * Checks every key put in mod.ini file
    * @param  array  $ini_array mod.ini file keys
    * @param  string $mod_type  Module type (in uppercase)
    * @return boolean           File OK
    */
   static function check_ini_keys($ini_array, $mod_type) {
      $var = "KEYS_MOD_$mod_type";
      $arr_keys = J3Utils::$var;

      foreach ($arr_keys as $key => $required) {
         if ($required === 1) {
            if (!isset($ini_array[$key])) {
               J3View::warning("Required key <strong>$key</strong> not found.");
               // TODO evaluate if use exit instead warning
               return false;
            }
         }
      }

      foreach ($ini_array as $key => $value) {
         if (!isset($arr_keys[$key])) {
            J3View::warning("Key <strong>$key</strong> not reconigzed.");
            // TODO evaluate if use exit instead warning
            return false;
         }
      }

      return true;
   }


   /**
    * Loads modules.ini file
    * @return void
    */
   static function load_ini_file() {
      if (J3ModuleLoader::mod_ini === null) {
         $mod_ini = parse_ini_file(J3Utils::FILE_INI_MODULES, true);
      }
   }

   /**
    * Verify if module and module type are enabled for load in modules.ini file
    * @param  string $type Module type
    * @param  string $mod  Module name
    * @return boolean      Allowed both
    */
   static function is_mod_allowed($type, $mod) {
      J3ModuleLoader::load_ini_file();
      if (J3ModuleLoader::mod_ini[$type] === null) {
         J3View::warning("Module type <strong>$type</strong> not reconigzed.");
         return false;
      }

      if (J3ModuleLoader::mod_ini[$type][J3Utils::MOD_INI_KEY_ENABLED] === 0) {
         J3View::warning("Module type <strong>$type</strong> not enabled.");
         return false;
      }

      $arr_mods = explode(',', J3ModuleLoader::mod_ini[$type][J3Utils::MOD_INI_KEY_LOAD]);
      if (!ini_array($mod, $arr_mods)) {
         J3View::warning("Module <strong>$type/$mod</strong> not enabled.");
         return false;
      }

      return true;
   }

   /* FUNCTIONS TO LOAD SPECIFIC MODULE TYPE */

   static function load_mod_database($ini_array) {
      return $ini_array;
   }

}

?>
