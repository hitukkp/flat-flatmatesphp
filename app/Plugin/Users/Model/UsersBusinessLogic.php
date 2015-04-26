<?php
/*
 * Handles Section related stuff
 * @author Hitesh Goel
 */

App::uses('AppModel', 'Users.Model');

class UsersBusinessLogic extends AppModel
{
    protected $usersModel = null;

    public $_fields = array(
        'USERS' => array(
              'user_id'             => array('table' => 'Users',  'column' => 'id'),
              'first_name'          => array('table' => 'Users',  'column' => 'first_name'),
              'last_name'           => array('table' => 'Users',  'column' => 'last_name'),
              'middle_name'         => array('table' => 'Users',  'column' => 'middle_name'),
              'sex'                 => array('table' => 'Users',  'column' => 'sex'),
              'email_id'            => array('table' => 'Users',  'column' => 'email_id'),
              'contact_no'          => array('table' => 'Users',  'column' => 'contact_no'),
              'pin_code'            => array('table' => 'Users',  'column' => 'pin_code'),
              'user_image'          => array('table' => 'Users',  'column' => 'user_image'),
              'date_of_birth'       => array('table' => 'Users',  'column' => 'date_of_birth'),
              'facebook_user_id'    => array('table' => 'Users',  'column' => 'facebook_user_id'),
              'is_owner'            => array('table' => 'Users',  'column' => 'is_owner'),
              'is_active'           => array('table' => 'Users',  'column' => 'is_active')
        )
    );

    public function user_get_result( $args )
    {
        try 
        {
            $filters = $args['queryFilters'];
            $fields = $args['queryFields'];

            if( ( isset( $filters['user_id'] ) || !empty( $filters['user_id'] ) ) && !empty( $args['resourceId'] ) )
            {
                throw new Exception( "User Id also passed in query String", 10010 );
            }
            else if( !empty( $args['resourceId'] ) )
            {
                $filters['user_id'] = $args['resourceId'];
            }
            
            $out = '';
            $out = $this->_get_users( $filters, $fields );
        }
        catch (Exception $e) 
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
        try
        {
            $out = null;

            if( isset( $args['requestData']['register'] ) && $args['requestData']['register'] == '1' )
            {
                $out = $this->_register_user( $args );
            }
            else if( isset( $args['requestData']['register'] ) && $args['requestData']['register'] == '0' )
            {
                $out = $this->_edit_user( $args );
            }

            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    private function _get_users( $filters, $fields )
    {    
        $type = 'all';
        $conditions = array();
        $limit = null;

        if( !empty( $filters ) )
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
            App::uses('UsersModel', 'Users.Model');

            $obj = new UsersModel();

            $out = $obj->getUsers( $type, $conditions, $limit, $joins, $fields );

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

        $allowedFilters = array(
            'contact_no' => '1',
            'user_id' => '1',
            'email_id' => '1',
            'facebook_user_id' => '1',
            'pin_code' => '1'
        );

        try
        {
            foreach ($filters as $key => $value) {
                
                if( !isset( $allowedFilters[$key] ) )
                {
                    throw new Exception("Invaid Filter Passed to UsersApi", 10011 );
                }
            }
        }
        catch(Exception $e){

            throw $e;
        }

        if( isset( $filters['user_id'] ) && !empty( $filters['user_id'] ) )
        {
            $options['Users.id'] = explode( ',', $filters['user_id'] );
        }
        if( isset( $filters['email_id'] ) && !empty( $filters['email_id'] ) )
        {
            $options['Users.email_id'] = explode( ',', $filters['email_id'] );
        }
        if( isset( $filters['contact_no'] ) && !empty( $filters['contact_no'] ) )
        {
            $options['Users.contact_no'] = explode( ',', $filters['contact_no'] );
        }
        if( isset( $filters['facebook_user_id'] ) && !empty( $filters['facebook_user_id'] ) )
        {
            $options['Users.facebook_user_id'] = explode( ',', $filters['facebook_user_id'] );
        }
        if( isset( $filters['pin_code'] ) && !empty( $filters['pin_code'] ) )
        {
            $options['Users.pin_code'] = explode( ',', $filters['pin_code'] );
        }

        return $options;
    }

    private function _getJoins( $filters )
    {
        $joins = array();
        
        return $joins;
    }

    private function _parseSingleResult( $result )
    {
        $temp1 = array();
        
        foreach ( $result as $key1 => $value1 ) 
        {
            $temp1 = array_merge( $temp1, $value1 );
        }

        return $temp1;
    }

    private function _parseResult( $result )
    {
        $temp1 = array();
        $temp2 = array();
        foreach ($result as $key => $value) 
        {
            foreach ( $value as $key1 => $value1 ) 
            {
                $temp1 = array_merge( $temp1, $value1 );
            }
            
            $temp2[$key] = $temp1;
        }

        return $temp2;
    }

    private function _register_user( $args ){
        
        $reqData = $args['requestData'];

        try
        {
            if( !isset( $reqData['email_id'] ) || empty( $reqData['email_id'] ) )
            {
                throw new Exception( "Email is required", 10004 );
            }
            if ( !filter_var( $reqData['email_id'], FILTER_VALIDATE_EMAIL ) ) 
            {
                throw new Exception( "Invalid Email sent", 10005 );
            }
            if( !isset( $reqData['first_name'] ) || empty( $reqData['first_name'] ) )
            {
                throw new Exception( "First Name is Required", 10006 );
            }
            if( !isset( $reqData['sex'] ) || empty( $reqData['sex'] ) )
            {
                throw new Exception( "Sex Field is Required", 10007 );
            }
            if( !isset( $reqData['facebook_user_id'] ) || empty( $reqData['facebook_user_id'] ) )
            {
                throw new Exception( "Facebook User Id is Required", 10008 );
            }

            $date_of_birth = date("Y-m-d",strtotime($reqData['date_of_birth']));

            $options = array(
                'first_name'        => isset( $reqData['first_name'] ) ? $reqData['first_name'] : 'Dummy User',
                'last_name'         => isset( $reqData['last_name'] ) ? $reqData['last_name'] : '',
                'middle_name'       => isset( $reqData['middle_name'] ) ? $reqData['middle_name'] : '',
                'sex'               => isset( $reqData['sex'] ) ? $reqData['sex'] : '0',
                'email_id'          => isset( $reqData['email_id'] ) ? $reqData['email_id'] : 'test@myroomi.com',
                'contact_no'        => isset( $reqData['contact_no'] ) ? $reqData['contact_no'] : '0011001100',
                'pin_code'          => isset( $reqData['pin_code'] ) ? $reqData['pin_code'] : '001100',
                'date_of_birth'     => $date_of_birth,
                'facebook_user_id'  => isset( $reqData['facebook_user_id'] ) ? $reqData['facebook_user_id'] : '001100',
                'is_owner'          => isset( $reqData['is_owner'] ) ? $reqData['is_owner'] : '0',
            );
            
            App::uses('UsersModel', 'Users.Model');

            $obj = new UsersModel();
            
            $out = $obj->saveUsers( $options, $validate = 'false' );
            
            return $out['Users'];
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    private function _edit_user( $args )
    {
        $reqData = $args['requestData'];

        try
        {
            if( !isset( $reqData['user_id'] ) || empty( $reqData['user_id'] ) )
            {
                throw new Exception( "User Id is required", 10009 );
            }
            if( !isset( $reqData['email_id'] ) || empty( $reqData['email_id'] ) )
            {
                throw new Exception( "Email is required", 10004 );
            }
            if ( !filter_var( $reqData['email_id'], FILTER_VALIDATE_EMAIL ) ) 
            {
                throw new Exception( "Invalid Email sent", 10005 );
            }
            if( !isset( $reqData['first_name'] ) || empty( $reqData['first_name'] ) )
            {
                throw new Exception( "First Name is Required", 10006 );
            }
            if( !isset( $reqData['sex'] ) || empty( $reqData['sex'] ) )
            {
                throw new Exception( "Sex Field is Required", 10007 );
            }
            if( !isset( $reqData['facebook_user_id'] ) || empty( $reqData['facebook_user_id'] ) )
            {
                throw new Exception( "Facebook User Id is Required", 10008 );
            }

            $date_of_birth = date("Y-m-d",strtotime($reqData['date_of_birth']));

            $options = array(
                'id'                => isset( $reqData['user_id'] ) ? $reqData['user_id'] : '',
                'first_name'        => isset( $reqData['first_name'] ) ? $reqData['first_name'] : 'Dummy User',
                'last_name'         => isset( $reqData['last_name'] ) ? $reqData['last_name'] : '',
                'middle_name'       => isset( $reqData['middle_name'] ) ? $reqData['middle_name'] : '',
                'sex'               => isset( $reqData['sex'] ) ? $reqData['sex'] : '0',
                'email_id'          => isset( $reqData['email_id'] ) ? $reqData['email_id'] : 'test@myroomi.com',
                'contact_no'        => isset( $reqData['contact_no'] ) ? $reqData['contact_no'] : '0011001100',
                'pin_code'          => isset( $reqData['pin_code'] ) ? $reqData['pin_code'] : '001100',
                'date_of_birth'     => $date_of_birth,
                'facebook_user_id'  => isset( $reqData['facebook_user_id'] ) ? $reqData['facebook_user_id'] : '001100',
                'is_owner'          => isset( $reqData['is_owner'] ) ? $reqData['is_owner'] : '0',
            );
            
            App::uses('UsersModel', 'Users.Model');

            $obj = new UsersModel();
            
            $out = $obj->saveUsers( $options, $validate = 'true' );
            
            return $out['Users'];
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }
}