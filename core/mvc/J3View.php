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
}

?>
