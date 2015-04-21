<?php 
Router::connect('/users/*', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'handler'));
?>
