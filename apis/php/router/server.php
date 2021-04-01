<?php
  
    require_once 'router.php';

    $Router1 = new router();

    $Router1->add('pages/login/index.html', function() {
        echo 'Success';
    });

    $Router1->run();
  
?>