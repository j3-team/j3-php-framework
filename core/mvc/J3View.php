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
 */

namespace J3\Core\Mvc;

use J3\Core\J3Utils;

class J3View {

   public $c;
   public $layout;
   public $view;

   public function __construct($controller, $layout, $view) {
      $this->view = $controller->getBaseName() . '/' . $view . '.php';
      $this->layout = $layout;
      $this->c = $controller;
      if (!isset($this->layout)) {
         $this->layout = J3Utils::DEFAULT_LAYOUT;
      }
   }

   public function render() {
      $c = $this->c;
      $v = $this;
      if (file_exists(J3Utils::DIR_MVC_LAYOUTS . $this->layout . '.php')) {
         require(J3Utils::DIR_MVC_LAYOUTS . $this->layout . '.php');
      } else {
         $this->viewContent();
      }
   }

   public function viewContent() {
      $c = $this->c;
      $v = $this;

      if (!file_exists(J3Utils::DIR_MVC_VIEWS . $this->view)) {
         echo "Archivo $this->view no existe!!";
      } else {
         require(J3Utils::DIR_MVC_VIEWS . $this->view);
      }
   }
}

?>
