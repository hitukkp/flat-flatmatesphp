<?php
App::uses('AppModel', 'Users.Model');
class UsersModel extends AppModel 
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

    public function saveUsers( $options, $validate = 'true' )
    {
        try
        {
            if( isset( $options ) )
            {
                $result = $this->save( $options, $validate );
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