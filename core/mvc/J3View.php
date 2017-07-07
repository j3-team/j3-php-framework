<?php
/**
 * J3 PHP Framework - core/mvc/J3View.php
 *
 * This file contains the base view class.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-01: Initial version
 *  2. 2017-07-03: Change messages language
 *  3. 2017-07-05: Create a new object $m with dynamic attributes
 *  4. 2017-07-06: Optimize mem use
 *                 AppBase HTML Tag
 *                 Functions to include CSS and JS files from INI file
 */

namespace J3\Core\Mvc;

require_once 'J3ControllerMethod.php';

use J3\Core\J3Utils;

class J3View {

   private $controller;
   private $layout;
   private $view;
   private $method;

   public function __construct($controller, $layout, $view, array $methodLocalVariables = array()) {
      $this->view = $controller->getBaseName() . '/' . $view;
      $this->layout = $layout;
      $this->controller = $controller;
      $this->method = new J3ControllerMethod($methodLocalVariables);
      if (!isset($this->layout)) {
         $this->layout = J3Utils::DEFAULT_LAYOUT;
      }
   }

   /**
    * Renders the view.
    */
   public function render() {
      $c = $this->controller;
      $v = $this;
      $m = $this->method;

      if (file_exists(J3Utils::DIR_MVC_LAYOUTS . $this->layout . '.php')) {
         require(J3Utils::DIR_MVC_LAYOUTS . $this->layout . '.php');
      } else {
         if ($this->layout !== J3Utils::DEFAULT_LAYOUT) {
            J3View::warning("Layout <strong>$this->layout</strong> not found.");
         }
         $this->viewContent();
      }
   }

   /**
    * Puts the view content on page.
    */
   public function viewContent() {
      $c = $this->controller;
      $v = $this;
      $m = $this->method;

      if (!file_exists(J3Utils::DIR_MVC_VIEWS . $this->view . '.php')) {
         J3View::warning ("View <strong>$this->view</strong> not found.");
      } else {
         require(J3Utils::DIR_MVC_VIEWS . $this->view . '.php');
      }
   }

   /**
    * Shows a WARNING system message.
    */
   public static function warning($message) {
      echo "<div style=\"top: 0px; position: relative; background-color: orange; color: black; text-align: center; padding: 5px 10px; margin: 0;font-size: 14px;\">J3 WARNING: $message</div>";
   }

   /**
    * Shows a INFO system message.
    */
   public static function info($message) {
      echo "<div style=\"top: 0px; position: relative; background-color: lightblue; color: black; text-align: center; padding: 5px 10px; margin: 0;font-size: 14px;\">$message</div>";
   }

   /**
    * Generates the HTML app base tag
    * @return String App Base HTML tag
    */
   public static function htmlAppBase() {
      $array = explode('/', $_SERVER["REQUEST_URI"],3);
      $type = 1;
      $path = $_SERVER['HTTP_HOST'].($type == 1 ? ('/'.$array[1]) : '');
      $url = "http://$path/";

      return '<base href="'. $url .'" />';
   }

   /**
    * Insert a CSS code from ini file
    * @param  String $section INI file section
    * @return String          HTML code for CSS include
    */
   public function includeCSS($section) {
      $ini_array = parse_ini_file(J3Utils::FILE_INI_RESOURCES, true);
      $to_print = "\n";
      foreach ($ini_array as $key => $value) {
         if ($key === $section) {
            if (isset($value['css'])) {
               foreach ($value['css'] as $key2) {
                  $css = '<link rel="stylesheet" type="text/css" href="resources/'.$key2.'" />';
                  $to_print = "$to_print   $css\n";
               }
            }
         }
      }

      return $to_print;
   }

   /**
    * Insert a JS code from ini file
    * @param  String $section INI file section
    * @return String          HTML code for JS include
    */
   public function includeJS($section) {
      $ini_array = parse_ini_file(J3Utils::FILE_INI_RESOURCES, true);
      $to_print = "\n";
      foreach ($ini_array as $key => $value) {
         if ($key === $section) {
            if (isset($value['js'])) {
               foreach ($value['js'] as $key2) {
                  $js = '<script type="text/javascript" src="resources/'.$key2.'"></script>';
                  $to_print = "$to_print   $js\n";
               }
            }
         }
      }

      return $to_print;
   }

}

?>
