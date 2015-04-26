<?php
/*
 * Class HousesApi
 * Handles Section related stuff
 * @author Hitesh Goel
 */

App::uses('HousesBusinessLogic', 'Houses.Model');

class HousesApi{

    protected $housesBusinessLogic = null;

    /**
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
    public function houses_get( $args )
    {
        try 
        {
            $this->housesBusinessLogic = new HousesBusinessLogic();
            $out = $this->housesBusinessLogic->houses_get_result( $args );
            return $out;
        } catch (Exception $e) 
        {
                throw $e;
        }
        
        return $out;
    }

    /**
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
    public function houses_put( $args ){

        try
        {
            $this->housesBusinessLogic = new HousesBusinessLogic();
            $out = $this->housesBusinessLogic->houses_put_result( $args );
            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    /**
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
    public function houses_post( $args )
    {
        try
        {
            $this->housesBusinessLogic = new HousesBusinessLogic();
            $out = $this->housesBusinessLogic->houses_post_result( $args );
            return $out;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
}