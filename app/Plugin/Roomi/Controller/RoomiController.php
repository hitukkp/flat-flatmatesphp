<?php
App::uses('RoomiAppController', 'Roomi.Controller');
class RoomiController extends RoomiAppController{
    
    public $layout = false;
    public $uses = array();
    protected $versions = array('v1');
    public $components = array('RequestHandler');
    protected $resources = array(
        'study_material'
    );

    protected $resourcesSubResources = array(
        'study_material'    => array('sm_slo_id')
    );

    protected $subresourcesSubResources = array(
        'subject'           => array('textbook')
    );
    protected $subresourcesSubResourcesSubResourses = array(
        'textbook'           => array('chapter')
    );

    protected function object_locator($resource)
    {
        $class = $resource;
        $className = $this->methodClassMap[$class];
        App::uses($className, 'ContentApi.');
        $object = new $className($class);
        return $object;
    }

    protected $methodClassMap = array(
        'study_material'    => 'StudyMaterialApi'
    );

    protected $inputErrors = array();
    protected $aggrecatedParameters = array(
        '1'     => 'today',
        '2'     => 'yesterday',
        '3'     => 'last_week',
        '4'     => 'last_month',
        '5'     => 'last_quarter',
        '6'     => 'last_halfyear',
        '7'     => 'last_year'
    );

    protected function get_method($resource,$subResource,$requestMethod){
        $method = null;
        $requestMethod = strtolower( $requestMethod );
        if( $subResource==null || is_numeric($subResource)) {
			$method = $this->getResourceMethod( $resource, $requestMethod );
        }
        else{
            $method = $this->getResourceMethod( $subResource, $requestMethod );
        }
        return $method;
    }

    protected function getResourceMethod( $resource = null , $requestMethod = null ){
        $map = array(
            'study_material'        => array(
                'get'   => 'study_material_get',
                'post'  => 'study_material_post',
                'put'   => 'study_material_put'
            )
        );
    
    if(is_array($map[$resource]) && array_key_exists($requestMethod ,$map[$resource] ))
             $method = $map[$resource][$requestMethod];
        else
             $method = $map[$resource];
        return $method;
    }

    public function beforeFilter()
    {
        //die('API not publicly exposed.'); //todo:uncomment
        //do not call parent's beforeFilter since we do not show any layout here
    }

    public function beforeRender(){
        // to skip parent before render
    }
    
    function handler($version = null, $resource = null, $resourceId = 0, $subResource = null, $subResourceId = null,  $subResource2 = null, $subResource2Id=null,$subResource3=null, $subResource3Id=null)
    {
        $out = null;
        try
        {       
            $resource = strtolower($resource);
            $subResource = strtolower($subResource);
            try
            {
                $requestMethod = strtoupper($this->request->method());
                $this->request_validator($this->request,$requestMethod);
                $args = $this->create_handler_argument($version, $resource, $resourceId, $subResource, $subResourceId, $subResource2,$subResource2Id,$subResource3,$subResource3Id,$this->request,$this->request->method());
                if( $requestMethod == 'POST' && isset( $args['request']->query['type'] ) && $args['request']->query['type'] == 'bulk_search' )
                {
                    foreach ($args['request']['data'] as $key => $value) 
                    {
                        $args['queryString']['filters'][$key]=$value;
                        $args['request']->query['filters'][$key] = $value;
                    }
                    $requestMethod = 'GET';
                }
                
                $object = $this->object_locator($resource, $subResource);
                $method = $this->get_method($resource,$subResource, $requestMethod);
                if(!method_exists($object, $method))
                {
                    throw new ContentApiException(array('method'),6);
                }
                $params = $this->_getParamas( $args );
                $out = $object->$method( $params );
                if(isset($out['status']))
                {
                    $out = $out;
                }
            } catch (Exception $e) 
            {
                $out = $this->exception_output($e);
            }
        }
        catch (Exception $e)
        {
            $out = exception_output($e);
        }
        $this->layout = false;
        $this->RequestHandler->renderAs($this, 'json');
        $this->emit($out);
    }
    
    private function request_validator($request=null,$requestMethod=null)
    {
        try {
            $params = $request->params['pass'];
            $queryString = $request->query;
            $numOfParameters = count($params);
            
            #echo '<pre>';print_r($params);
            $param1 = isset($params[0])?$params[0]:0; //version
            $param2 = isset($params[1])?$params[1]:0; //resource
            $param3 = isset($params[2])?$params[2]:0;
            $param4 = isset($params[3])?$params[3]:0;
            $param5 = isset($params[4])?$params[4]:0;
            $param6 = isset($params[5])?$params[5]:0;
            $param7 = isset($params[6])?$params[6]:0;  
            $param8 = isset($params[7])?$params[7]:0;
            $param9 = isset($params[8])?$params[8]:0;
            
            if (empty($param1) || empty($param2)) {
                throw new ContentApiException('', 1);
            }
            $param1 = strtolower(trim($param1));
            $param2 = strtolower(trim($param2));
            $param3 = trim($param3);
            if (!in_array($param1, $this->versions)) { // check version
                 throw new ContentApiException(array('version number'), 6);
            }
            if (!in_array($param2, $this->resources)) { //check resourses
                 throw new ContentApiException(array('resource'), 6);
            }
            if($param4){
                $this->parseInt($param3, 'resource id'); //first resource id must be int
            }
            
            if($numOfParameters==3 && !empty($this->request->query)){
                $queryString = $this->request->query;
                if(isset($queryString['format']) && strtolower($queryString['format'])!='aggregate'){
                    throw new ContentApiException(array('query string'), 6);
                }
                if(isset($queryString['format']) && strtolower($queryString['format'])=='aggregate'){ 
                    if(!isset($queryString['over'])){
                        throw new ContentApiException(array('query string'), 6);
                    }
                    $over = explode(',', $queryString['over']);
                    $isMatchedAggregated= array_diff($over,$this->aggrecatedParameters);
                    if(!empty($isMatchedAggregated)){
                        throw new ContentApiException('',9);
                    }
                }
            }
            if($numOfParameters==4 && $param2!='marketing'){
                $this->parseInt($param3, 'resource id');
                $param4 = strtolower(trim($param4));
                if(!in_array($param4 ,$this->resourcesSubResources[$param2])){
                    throw new ContentApiException('', 5);
                }
                #$this->parseInt($param4, 'sub-resource id');
            }
            $flagFor7 = true;
            if(in_array($numOfParameters, array(5,7,8,9))){
                $param4 = strtolower(trim($param4));
                if(!in_array($param4 ,$this->resourcesSubResources[$param2])){
                    throw new ContentApiException('', 5);
                }
                $this->parseInt($param5, 'sub-resource id');
                $flagFor7 = FALSE;
            }
            $flagFor8 = true;
            if(in_array($numOfParameters, array(7,8,9))){
                if($flagFor7){
                    throw new ContentApiException('', 2);
                }
                $param6 = strtolower(trim($param6));
                if(!isset($this->subresourcesSubResources[$param4])){
                    throw new ContentApiException('', 5);
                }
                if(!in_array($param6, $this->subresourcesSubResources[$param4])){
                    throw new ContentApiException('', 5);
                }
                $this->parseInt($param7, 'sub-resource id');
                $flagFor8 = FALSE;
            }  
            if(in_array($numOfParameters, array(8,9))){
                if($flagFor7 || $flagFor8){
                    throw new ContentApiException('', 2);
                }
                $param8 = strtolower(trim($param8));
                if(!isset($this->subresourcesSubResourcesSubResourses[$param6])){
                    throw new ContentApiException('', 5);
                }
                if(!in_array($param8, $this->subresourcesSubResourcesSubResourses[$param6])){
                    throw new ContentApiException('', 5);
                }
                $this->parseInt($param7, 'sub-resource id');
            } 
        } catch (Exception $e){
            throw $e;
        }
    }

    protected function create_handler_argument($version, $resource, $resourceId, $subResource, $subResourceId, $subResource2,$subResource2Id,$subResource3,$subResource3Id, $request,$method){
        $data = array(
            'version'       => $version,
            'resource'      => $resource,
            'resourceId'    => $resourceId,
            'subResource'   => $subResource,
            'subResourceId' => $subResourceId,
            'subResource2'  => $subResource2,
            'subResource2Id'=> $subResource2Id,
            'subResource3'  => $subResource3,
            'subResource3Id'=> $subResource3Id,
            'http_method'   => $method,
            'queryString'   => $request->query,
            'request'       => $request
        );
        return $data;
    }
    
    /**
     * This method parses the $value for an integer value
     * @param mixed $value value of the field
     * @param string $name name of the field
     * @throws Exception
     * @author Aneesh Khan<aneesh.khan@meritnation.com>
     */
    protected function parseInt(&$value, $name = 'id')
    {
        try {
            if (empty($value)) {
                throw new ContentApiException(array($name), 4);
            }
            if (is_string($value) && ctype_digit($value)) {
                $value = (int)$value;
            }
            //below 'or' condition follows when above integer casting returns 0.
            if (!is_int($value) || empty($value)) {
                throw new ContentApiException(array($name), 6);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * This method returns the query data filled in URL.
     * @param mixed $value value of the field
     * @throws Exception
     * @author Hitesh Goel
     */
    private function _getParamas( $args )
    {
        $data = array(
            'resourceId'    => $args['resourceId'],
            'subResourceId' => $args['subResourceId'],
            'subResource2Id'=> $args['subResource2Id'],
            'subResource3Id'=> $args['subResource3Id'],
            'filters'       => isset( $args['queryString']['filters'] ) ? $args['queryString']['filters'] : array(),
            'fields'        => isset( $args['queryString']['projection'] ) ? explode( ',', $args['queryString']['projection'] ) : array(),
            'requestData'   => $args['request']['data']
        );
        unset( $args['queryString']['filters'] );
        unset( $args['queryString']['projection'] );
        $data['queryParams'] = $args['queryString'];
        return $data;
    }
}
?>