<?php 
Router::connect('/houses/*', array('plugin' => 'Houses', 'controller' => 'houses', 'action' => 'handler'));
?>
