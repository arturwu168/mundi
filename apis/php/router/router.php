<?php
   
   class router {
        public function __construct() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->url = $_SERVER['PATH_INFO'];
        }

        public function add($route, $callback) {
            $this->rules[] = array(
                'route' => $route,
                'callback' => $callback
            );
        }

        public function run() {
            foreach($this->rules as $rule) {
                if($rule['route'] == $this->url) {
                    $rule['callback']();
                    break;
                }
            }
        }
    }

?>