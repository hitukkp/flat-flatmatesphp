<?php
App::uses('UsersAppController', 'Users.Controller');
class UsersController extends UsersAppController{
    
    public $layout = false;
    public $uses = array();
    protected $versions = array('v1');
    public $components = array('RequestHandler');
    protected $resources = array('users');
    protected $methodClassMap = array('users' => 'UsersApi');

    public function beforeFilter()
    {
        //die('API not publicly exposed.'); //todo:uncomment
        //do not call parent's beforeFilter since we do not show any layout here
    }

    public function beforeRender(){
        // to skip parent before render
    }

    protected $resourcesSubResources = array(
        //'study_material'    => array('sm_slo_id')
    );

    protected function object_locator($resource)
    {
        $class = $resource;
        $className = $this->methodClassMap[$class];
        App::uses($className, 'Users.');
        $object = new $className($class);
        return $object;
    }

    protected $inputErrors = array();

    protected function get_method($resource,$subResource,$requestMethod)
    {
        $method = null;
        $requestMethod = strtolower( $requestMethod );

        if( $subResource == null || is_numeric( $subResource ) ) 
        {
			$method = $this->getResourceMethod( $resource, $requestMethod );
        }
        else
        {
            $method = $this->getResourceMethod( $subResource, $requestMethod );
        }
        
        return $method;
    }

    protected function getResourceMethod( $resource = null , $requestMethod = null )
    {
        $map = array(
            'users'        => array(
                'get'   => 'users_get',
                'post'  => 'users_post',
                'put'   => 'users_put'
            )
        );
    
        $method = $map[$resource][$requestMethod];

        return $method;
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
                
                //Create Arguments to be Passed to the API.
                $args = $this->create_handler_argument($version, $resource, $resourceId, $subResource, $subResourceId, $subResource2,$subResource2Id,$subResource3,$subResource3Id,$this->request,$this->request->method());
                
                //Support for Bulk Search thorugh Post api.
                if( $requestMethod == 'POST' && isset( $args['request']->query['type'] ) && $args['request']->query['type'] == 'bulk_search' )
                {
                    foreach ($args['request']['data'] as $key => $value) 
                    {
                        $args['queryString']['filters'][$key]=$value;
                        $args['request']->query['filters'][$key] = $value;
                    }
                    $requestMethod = 'GET';
                }
                
                //Creates the Object to be called from that class.
                $object = $this->object_locator($resource, $subResource);

                //Calls the Method to be fetched.
                $method = $this->get_method($resource,$subResource, $requestMethod);
                
                //Throws the Exception if method is not there in the Class.
                if(!method_exists($object, $method))
                {
                    throw new ContentApiException(array('method'),6);
                }

                //Calls the method and expects the result.
                $out = $object->$method( $args );
                if(isset( $out['status'] ))
                {
                    $out = $out;
                }
            } catch ( Exception $e )
            {
                $out = $this->exception_output( $e );
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
    
    private function request_validator( $request=null, $requestMethod=null )
    {
        try 
        {
            $params = $request->params['pass'];
            $queryString = $request->query;
            $numOfParameters = count($params);
            
            $param1 = isset($params[0])?$params[0]:0; //version
            $param2 = isset($params[1])?$params[1]:0; //resource
            $param3 = isset($params[2])?$params[2]:0; //resourceId
            $param4 = isset($params[3])?$params[3]:0; //subresource
            $param5 = isset($params[4])?$params[4]:0; //subresourceId
            
            if (empty($param1) || empty($param2)) 
            {
                throw new ContentApiException( 'Version No and Resource Name must be provided', 1 );
            }

            $param1 = strtolower(trim($param1));
            $param2 = strtolower(trim($param2));
            $param3 = trim( $param3 );

            // check correct version
            if (!in_array($param1, $this->versions)) 
            {
                throw new ContentApiException(array('version number'), 6);
            }

            //check resourses
            if (!in_array($param2, $this->resources)) 
            {
                throw new ContentApiException(array('resource'), 6);
            }
            
        } catch (Exception $e){
            throw $e;
        }
    }

    protected function create_handler_argument($version, $resource, $resourceId, $subResource, $subResourceId, $subResource2,$subResource2Id,$subResource3,$subResource3Id, $request,$method)
    {
        $queryString = $request->query;
        $queryFilters = isset( $queryString['filters'] ) ? $queryString['filters'] : array();
        $queryFields = isset( $queryString['projection'] ) ? explode( ',', $queryString['projection'] ) : array();

        unset( $queryString['filters'] );
        unset( $queryString['projection'] );

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
            'queryFilters'  => $queryFilters,
            'queryFields'   => $queryFields,
            'requestData'   => $request->data,
            'queryString'   => $queryString,
            'http_method'   => $method
        );

        return $data;
    }
}
?>