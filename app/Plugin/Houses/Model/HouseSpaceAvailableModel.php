<?php
App::uses('AppModel', 'Houses.Model');
class HouseSpaceAvailableModel extends AppModel 
{
    public $name = "HouseSpaceAvailable";
    public $useTable = 'house_space_available';

    public function getHouseSpaceAvailable( $type = 'all', $conditions = array(), $limit = null, $joins = array(), $fields = array() )
    {
		try
		{
    		$result = $this->find(
    			$type,
    			array(
    				'conditions'=> $conditions,
                    'limit'     => $limit,
    				'joins'		=> $joins,
    				'fields' 	=> $fields
    			)
    		);
    		
    		return $result;
		}
		catch(Exception $e)
		{
			throw $e;
		};
    }

    public function saveHouseSpaceAvailable( $options, $validate = 'true' )
    {
        try
        {
            if( isset( $options ) )
            {
                $result = $this->save( $options, $validate );
                return $result;
            }else
            {
                throw new Exception("Invalid HouseSpaceAvailable Parameters Passed", 200013);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        
    }
}
?>