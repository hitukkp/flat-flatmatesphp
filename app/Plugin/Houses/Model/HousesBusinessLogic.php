<?php
/*
 * Handles Section related stuff
 * @author Hitesh Goel
 */

App::uses('AppModel', 'Users.Model');

class HousesBusinessLogic extends AppModel
{
    protected $housesModel = null;

    public $_fields = array(
        'HOUSES' => array(
              'house_id'            => array('table' => 'HouseDetails',  'column' => 'id')
        )
    );

    public function houses_get_result( $args )
    {
        try 
        {
            $out = '';
        }
        catch (Exception $e) 
        {
                throw $e;
        }
        
        return $out;
    }

    public function houses_put_result( $args )
    {
        try
        {
            
            $out = $this->publish_textbook( $args );

            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    public function houses_post_result( $args )
    {
        try
        {
            $out = null;

            if( isset( $args['requestData']['add_house'] ) && $args['requestData']['add_house'] == '1' )
            {
                $out = $this->_add_house( $args );
            }
            else if( isset( $args['requestData']['add_house'] ) && $args['requestData']['add_house'] == '0' )
            {
                $out = $this->_edit_house( $args );
            }

            return $out;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }

    private function _add_house( $args )
    {
        $reqData = $args['requestData'];

        try
        {
            if( !isset( $reqData['house_type'] ) || empty( $reqData['house_type'] ) )
            {
                throw new Exception( "house_type is required", 20004 );
            }
            if( !isset( $reqData['user_id'] ) || empty( $reqData['user_id'] ) )
            {
                throw new Exception( "User Id is Required", 20005 );
            }
            if( !isset( $reqData['prop_bedrooms'] ) || empty( $reqData['prop_bedrooms'] ) )
            {
                throw new Exception( "Properties Bedrooms is Required", 20007 );
            }
            if( !isset( $reqData['avlbl_rooms'] ) || empty( $reqData['avlbl_rooms'] ) )
            {
                throw new Exception( "available space is Required", 20008 );
            }
            if( !isset( $reqData['avlbl_room_is_shared'] ) )
            {
                throw new Exception( "avlbl_room_is_shared is Required", 20006 );
            }
            if( !isset( $reqData['avlbl_security_deposit'] ) || empty( $reqData['avlbl_security_deposit'] ) )
            {
                throw new Exception( "security deposit details are Required", 20009 );
            }
            if( !isset( $reqData['avlbl_rent'] ) || empty( $reqData['avlbl_rent'] ) )
            {
                throw new Exception( "provide the rent details", 20010 );
            }
            if( !isset( $reqData['avlbl_from'] ) || empty( $reqData['avlbl_from'] ) )
            {
                throw new Exception( "avlbl_from date is Required", 20011 );
            }

            $availableFrom = date("Y-m-d",strtotime($reqData['avlbl_from']));

            $propertyDetails = array(
                'user_id'           => isset( $reqData['user_id'] ) ? $reqData['user_id'] : '',
                'house_type_id'     => isset( $reqData['house_type'] ) ? $reqData['house_type'] : '3',
                'max_persons'       => isset( $reqData['prop_max_persons'] ) ? $reqData['prop_max_persons'] : '',
                'bath_rooms'        => isset( $reqData['prop_bathrooms'] ) ? $reqData['prop_bathrooms'] : '',
                'bed_rooms'         => isset( $reqData['prop_bedrooms'] ) ? $reqData['prop_bedrooms'] : '',
                'kitchens'          => isset( $reqData['prop_kitchen'] ) ? $reqData['prop_kitchen'] : '',
                'total_rent'        => isset( $reqData['prop_rent'] ) ? $reqData['prop_rent'] : '',
                'title'             => isset( $reqData['prop_title'] ) ? $reqData['prop_title'] : '',
                'security_deposit'  => isset( $reqData['prop_security_deposit'] ) ? $reqData['prop_security_deposit'] : '',
                'description'       => isset( $reqData['prop_description'] ) ? $reqData['prop_description'] : '',
                'instrunctions'     => isset( $reqData['prop_instrunctions'] ) ? $reqData['prop_instrunctions'] : '',
                'minimum_stay'      => isset( $reqData['prop_minstay'] ) ? $reqData['prop_minstay'] : '',
            );

            App::uses('HouseDetailsModel', 'Houses.Model');

            $houseDetailsModel = new HouseDetailsModel();

            $outProp = $houseDetailsModel->saveHouseDetails( $propertyDetails, $validate = 'false' );
            
            $availableSpaceDetails = array(
                'house_id'          => $outProp['HouseDetails']['id'],
                'rooms_available'   => isset( $reqData['avlbl_rooms'] ) ? $reqData['avlbl_rooms'] : '1',
                'is_shared'         => isset( $reqData['avlbl_room_is_shared'] ) ? $reqData['avlbl_room_is_shared'] : '0',
                'attached_bathroom' => isset( $reqData['avlbl_attached_bathroom'] ) ? $reqData['avlbl_attached_bathroom'] : '0',
                'bed'               => isset( $reqData['avlbl_beds'] ) ? $reqData['avlbl_beds'] : '0',
                'balcony'           => isset( $reqData['avlbl_balcony'] ) ? $reqData['avlbl_balcony'] : '0',
                'rent'              => isset( $reqData['avlbl_rent'] ) ? $reqData['avlbl_rent'] : '',
                'available_from'    => $availableFrom,
                'security_deposit'  => isset( $reqData['avlbl_security_deposit'] ) ? $reqData['avlbl_security_deposit'] : ''
            );

            App::uses('HouseSpaceAvailableModel', 'Houses.Model');

            $houseSpaceAvailableModel = new HouseSpaceAvailableModel();

            $outAvlbl = $houseSpaceAvailableModel->saveHouseSpaceAvailable( $availableSpaceDetails, $validate = 'false' );

            $ownerPreferencesDetails = array(
                'house_id'          => $outProp['HouseDetails']['id'],
                'profession'        => isset( $reqData['pref_occupation'] ) ? $reqData['pref_occupation'] : '3',
                'food_pref'         => isset( $reqData['pref_food'] ) ? $reqData['pref_food'] : '',
                'smoking'           => isset( $reqData['pref_smok_drink'] ) ? $reqData['pref_smok_drink'] : '',
                'drinking'          => isset( $reqData['pref_smok_drink'] ) ? $reqData['pref_smok_drink'] : '',
                'guests'            => isset( $reqData['pref_guests'] ) ? $reqData['pref_guests'] : '',
                'pets'              => isset( $reqData['pref_pets'] ) ? $reqData['pref_pets'] : '',
                'gender'            => isset( $reqData['pref_gender'] ) ? $reqData['pref_gender'] : ''
            );

            App::uses('OwnerPreferencesModel', 'Houses.Model');

            $ownerPreferencesModel = new OwnerPreferencesModel();

            $outPref = $ownerPreferencesModel->saveOwnerPreferences( $ownerPreferencesDetails, $validate = 'false' );

            $userDetails = array(
                'id'                => isset( $reqData['user_id'] ) ? $reqData['user_id'] : '',
                'is_owner'          => '1'
            );
            
            App::uses('UsersModel', 'Users.Model');

            $obj = new UsersModel();
            
            $outUsers = $obj->saveUsers( $userDetails, $validate = 'true' );
            
            return $outProp['HouseDetails']['id'];
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }
}