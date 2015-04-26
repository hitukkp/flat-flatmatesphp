<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Application CmsModel for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
App::uses('Model', 'Model');
class AppModel extends Model
{
	public function _getFields( $fieldsType, $projectedFields, $filters )
    {
        $fields = $this->_getMapFields( $fieldsType, $projectedFields );
        return $fields;
    }

	public function _getMapFields( $type,  $projectedFields )
    {
        $out = array();

        $fields = $this->_fields[$type];

        if( !empty( $projectedFields ) )
        {
            foreach ( $projectedFields as $key => $value )
            {
                if( isset( $fields[$value] ) )
                {
                    $out[$value] = $fields[$value]['table'] . '.' . $fields[$value]['column'] . ' AS ' . $value;                        
                }
            }
        }
        else
        {
            foreach ($fields as $key => $value)
            {
                if ( is_array( $value ) ) 
                {
                    $out[$key] = $value['table'] . '.' . $value['column'] . ' AS ' . $key;
                }
            }
        }
        return $out;
    }
}