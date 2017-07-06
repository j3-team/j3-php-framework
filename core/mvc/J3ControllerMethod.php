<?php
/**
 * J3 PHP Framework - core/mvc/J3ControllerMethod.php
 *
 * This file contains a class for store method variables purpose.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-05: Initial version
 */

namespace J3\Core\Mvc;

class J3ControllerMethod {

   public function __construct(array $arguments = array()) {
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                if ($argument instanceOf Closure) {
                    $this->{$property} = $argument;
                } else {
                    $this->{$property} = $argument;
                }
            }
        }
    }

}

?>
