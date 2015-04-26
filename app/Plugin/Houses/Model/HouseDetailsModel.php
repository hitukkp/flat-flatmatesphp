<?php
App::uses('AppModel', 'Houses.Model');
class HouseDetailsModel extends AppModel 
{
    public $name = "HouseDetails";
    public $useTable = 'house_details';

    public function getHouseDetails( $type = 'all', $conditions = array(), $limit = null, $joins = array(), $fields = array() )
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

    public function saveHouseDetails( $options, $validate = 'true' )
    {
        try
        {
            if( isset( $options ) )
            {
                $result = $this->save( $options, $validate );
                return $result;
            }else
            {
                throw new Exception("Invalid HouseDetails Parameters Passed", 200014);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        
    }
}
?>