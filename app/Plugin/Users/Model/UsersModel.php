<?php
App::uses('AppModel', 'Users.Model');
class Textbook extends AppModel 
{
    public $name = "Users";
    public $useTable = 'users';

    public function getUsers( $type = 'all', $conditions = array(), $limit = null, $joins = array(), $fields = array() )
    {
		try
		{
    		$result = $this->find(
    			$type,
    			array(
    				'conditions'=> $conditions,
    				'order' 	=> array('Textbook.id' => 'asc'),
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

    public function saveUsers( $options )
    {
        try
        {
            if( isset( $options ) )
            {
                $result = $this->save( $options );
                return $result;
            }else
            {
                throw new Exception("Invalid Parameters Passed", 11001);
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
        
    }
}
?>