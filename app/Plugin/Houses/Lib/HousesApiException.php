<?php
/**
 * Class ContentApiException
 * Handles exceptions thrown by api
 */
class HousesApiException extends Exception
{
	public static $codes = array(
		//Exceptions thrown in BaseQuestionApi class
		20000 => 'Version No and Resource Name must be provided',
		20001 => 'Version No incorrect',
		20002 => 'Wrong Resource passed',
		20003 => 'Method Does Not exist.',
		20004 => 'house_type is required',
		20005 => 'User Id is Required',
		20006 => 'is_room_shared is Required',
		20007 => 'Properties Bedrooms is Required',
		20008 => 'available space is Required',
		20009 => 'security deposit details are Required',
		20010 => 'provide the rent detail',
		20011 => 'avlbl_from date is Required',
		20012 => 'Invalid Parameters Passed',
		20013 => 'Invalid HouseSpaceAvailable Parameters Passed',
		20014 => 'Invalid HouseDetails Parameters Passed'
	);

	public function __construct( $message = '', $code = 0, Exception $previous = null )
	{
		if ( empty($message) && !empty(self::$codes[$code]) ) 
		{
			$message = self::$codes[$code];
		} else if (is_array($message) && !empty(self::$codes[$code]))
		{
			$message = vsprintf(self::$codes[$code], $message);
		}
		parent::__construct( $message, $code, $previous );
	}
}
