<?php
/**
 * Class ContentApiException
 * Handles exceptions thrown by api
 * @author Rajesh Pant<rajesh.pant@meritnation.com>
 * @created 22 July 2014
 */
class UsersApiException extends Exception
{
	public static $codes = array(
		//Exceptions thrown in BaseQuestionApi class
		10000 => 'Version No and Resource Name must be provided',
		10001 => 'Version No incorrect',
		10002 => 'Wrong Resource passed',
		10003 => 'Method Does Not exist.',
		10004 => 'email_id field is blank',
		10005 => 'Invalid Email sent',
		10006 => 'first_name is blank',
		10007 => 'Sex field is blank',
		10008 => 'facebook_user_id field is blank',
		10009 => 'user_id is required',
		10010 => 'user_id also passed in query String',
		10011 => 'Invaid Filter Passed to UsersApi'
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
