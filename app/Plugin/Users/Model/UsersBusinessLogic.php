<?php
/*
 * Class TextbookApi
 * Handles Section related stuff
 * @author Hitesh Goel
 */

App::uses('AppModel', 'Users.Model');
App::uses('UsersModel', 'Users.Model');

class UsersBusinessLogic extends AppModel
{
    protected $usersModel = null;

    protected $_fields = array(
        'USERS' => array(
              //Tests table fields
              //'textbook_id'         => array('table' => 'Textbook',  'column' => 'id'),
        )
    );

    public function user_get_result( $args )
    {
        try 
        {
            $out = '';
            $filters = $args['filters'];
            $fields = $args['fields'];
            $lid = $args['resourceId'];
            
            if( !empty( $args['queryParams']['page'] ) )
            {
                $page = $args['queryParams']['page'];
            }
            
            $out = $this->_get_user_list( $filters ,$page, $fields );

        } catch (Exception $e) 
        {
                throw $e;
        }
        
        return $out;
    }

    public function user_put_result( $args )
    {
        try
        {
            
            $out = $this->publish_textbook( $args );

            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    public function user_post_result( $args )
    {
        echo "here";die;
        try
        {
            $out = $this->publish_textbook( $args );

            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    private function _get_user_list( $filters, $page, $fields )
    {    
        $type = 'all';
        $conditions = array();
        $limit = null;

        if(!empty( $filters ))
        {
              $conditions = $this->_getConditions( $filters );
        }
        else
        {
            $limit = 20;
        }

        $joins = array();
        $joins = $this->_getJoins( $filters );

        $fields = $this->_getFields( 'USERS', $fields, $filters );

        try
        {
            $this->usersModel = new UsersModel();
            $out = $this->usersModel->getUsers( $type, $conditions, $limit, $joins, $fields );
            $resultOut = $this->_parseResult( $out );
            return $resultOut;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    private function _getConditions( $filters ){

        $options = array();

        return $options;
    }

    private function _getJoins( $filters )
    {
        $joins = array();
        
        return $joins;
    }

    private function _parseSingleResult( $result ){
        $temp1 = array();
        foreach ( $result as $key1 => $value1 ) {
              $temp1 = array_merge( $temp1, $value1 );
        }
        return $temp1;
    }

    private function _parseResult( $result ){
        $temp1 = array();
        $temp2 = array();
        foreach ($result as $key => $value) {
              foreach ( $value as $key1 => $value1 ) {
                    $temp1 = array_merge( $temp1, $value1 );
              }
              $temp2[$key] = $temp1;
        }
        return $temp2;
    }
}