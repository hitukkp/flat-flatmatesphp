<?php
App::uses('AppModel', 'Houses.Model');
class OwnerPreferencesModel extends AppModel 
{
    public $name = "OwnerPreferences";
    public $useTable = 'owner_preferences';

    public function getOwnerPreferences( $type = 'all', $conditions = array(), $limit = null, $joins = array(), $fields = array() )
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

    public function saveOwnerPreferences( $options, $validate = 'true' )
    {
        try
        {
            if( isset( $options ) )
            {
                $result = $this->save( $options, $validate );
                return $result;
            }else
            {
                throw new Exception("Invalid Parameters Passed", 200012);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        
    }
}
?>