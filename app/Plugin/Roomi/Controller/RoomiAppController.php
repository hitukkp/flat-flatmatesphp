<?php
class RoomiAppController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
    }

    function exception_output($e){ 
        $exceptionData = array(
           "status" => 0,
           "response_code" => $e->getCode(),
           "message" => $e->getMessage()
        );
        return $exceptionData;
    }

    function standard_output($data){
        return array(
            "status"=> 1,
            "response_code" => 0,
            "message"=>"",
            "data" =>$data
        );
    }

    function emit($result){
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
?>
