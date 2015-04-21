<?php
/*
 * Class UsersApi
 * Handles Section related stuff
 * @author Hitesh Goel
 */

App::uses('UsersBusinessLogic', 'Users.Model');

class UsersApi{

    protected $userBusinessLogic = null;

    /**
     * This method returns the Users by ID or get a list of Users.
     * @param An Array of Arguments 
     * @Example Array { These are optional parameters }
        (
            [resourceId] => 100
            [subResourceId] => 101
            [subResource2Id] => 102
            [subResource3Id] => 103
            [filters] => Array
            (
                [id] => 100,1001,10032
            )
            [fields] => Array
            (
                [0] => sloId
                [1] => id
            )
            [requestData] => Array // Post Paramteres
            (
                [reqData1] = 100
                [reqData2] = 101
            )
            [queryString] => Array
            (
                [key] => value
            )
        )
     * 
    */
    public function users_get( $args )
    {
        try 
        {
            $this->userBusinessLogic = new UsersBusinessLogic();
            $out = $this->userBusinessLogic->user_get_result( $args );
            return $out;
        } catch (Exception $e) 
        {
                throw $e;
        }
        
        return $out;
    }

    /**
     * This method Deativate or Map an existing User.
     * @param An Array of Arguments 
     * @Example Array { These are optional parameters }
        (
            [resourceId] => 100
            [subResourceId] => 101
            [subResource2Id] => 102
            [subResource3Id] => 103
            [filters] => Array
            (
                [id] => 100,1001,10032
            )
            [fields] => Array
            (
                [0] => sloId
                [1] => id
            )
            [requestData] => Array // Post Paramteres
            (
                [reqData1] = 100
                [reqData2] = 101
            )
            [queryParams] => Array
            (
                [key] => value
            )
        )
     * 
    */
    public function users_put( $args ){

        try
        {
            $this->userBusinessLogic = new UsersBusinessLogic();
            $out = $this->userBusinessLogic->user_put_result( $args );
            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    /**
     * This method Creates a new Users or updates an existing User.
     * @param An Array of Arguments 
     * @Example Array { These are optional parameters }
        (
            [resourceId] => 100
            [subResourceId] => 101
            [subResource2Id] => 102
            [subResource3Id] => 103
            [filters] => Array
            (
                [id] => 100,1001,10032
            )
            [fields] => Array
            (
                [0] => sloId
                [1] => id
            )
            [requestData] => Array // Post Paramteres
            (
                [reqData1] = 100
                [reqData2] = 101
            )
            [queryParams] => Array
            (
                [key] => value
            )
        )
     * 
    */
    public function users_post( $args )
    {
        try
        {
            $this->userBusinessLogic = new UsersBusinessLogic();
            $out = $this->userBusinessLogic->user_post_result( $args );
            return $out;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
}
