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
            if( !isset( $reqData['property_type']['house_type'] ) || empty( $reqData['property_type']['house_type'] ) )
            {
                throw new Exception( "house_type is required", 20004 );
            }
            if( !isset( $reqData['user_details']['user_id'] ) || empty( $reqData['user_details']['user_id'] ) )
            {
                throw new Exception( "User Id is Required", 20005 );
            }
            if( !isset( $reqData['property_description']['prop_bedrooms'] ) || empty( $reqData['property_description']['prop_bedrooms'] ) )
            {
                throw new Exception( "Properties Bedrooms is Required", 20007 );
            }
            if( !isset( $reqData['room_description']['avlbl_rooms'] ) || empty( $reqData['room_description']['avlbl_rooms'] ) )
            {
                throw new Exception( "available space is Required", 20008 );
            }
            if( !isset( $reqData['room_description']['avlbl_room_is_shared'] ) )
            {
                throw new Exception( "avlbl_room_is_shared is Required", 20006 );
            }
            if( !isset( $reqData['room_description']['avlbl_security_deposit'] ) || empty( $reqData['room_description']['avlbl_security_deposit'] ) )
            {
                throw new Exception( "security deposit details are Required", 20009 );
            }
            if( !isset( $reqData['room_description']['avlbl_rent'] ) || empty( $reqData['room_description']['avlbl_rent'] ) )
            {
                throw new Exception( "provide the rent details", 20010 );
            }
            if( !isset( $reqData['room_description']['avlbl_from'] ) || empty( $reqData['room_description']['avlbl_from'] ) )
            {
                throw new Exception( "avlbl_from date is Required", 20011 );
            }

            $availableFrom = date("Y-m-d",strtotime($reqData['room_description']['avlbl_from']));

            $propertyDetails = array(
                'user_id'           => isset( $reqData['user_details']['user_id'] ) ? $reqData['user_details']['user_id'] : '',
                'house_type_id'     => isset( $reqData['property_type']['house_type'] ) ? $reqData['property_type']['house_type'] : '3',
                'max_persons'       => isset( $reqData['property_description']['prop_max_persons'] ) ? $reqData['property_description']['prop_max_persons'] : '',
                'bath_rooms'        => isset( $reqData['property_description']['prop_bathrooms'] ) ? $reqData['property_description']['prop_bathrooms'] : '',
                'bed_rooms'         => isset( $reqData['property_description']['prop_bedrooms'] ) ? $reqData['property_description']['prop_bedrooms'] : '',
                'kitchens'          => isset( $reqData['property_description']['prop_kitchen'] ) ? $reqData['property_description']['prop_kitchen'] : '',
                'total_rent'        => isset( $reqData['property_description']['prop_rent'] ) ? $reqData['property_description']['prop_rent'] : '',
                'title'             => isset( $reqData['property_description']['prop_title'] ) ? $reqData['property_description']['prop_title'] : '',
                'security_deposit'  => isset( $reqData['property_description']['prop_security_deposit'] ) ? $reqData['property_description']['prop_security_deposit'] : '',
                'description'       => isset( $reqData['property_description']['prop_description'] ) ? $reqData['property_description']['prop_description'] : '',
                'instrunctions'     => isset( $reqData['property_description']['prop_instrunctions'] ) ? $reqData['property_description']['prop_instrunctions'] : '',
                'minimum_stay'      => isset( $reqData['property_description']['prop_minstay'] ) ? $reqData['property_description']['prop_minstay'] : '',
            );

            App::uses('HouseDetailsModel', 'Houses.Model');

            $houseDetailsModel = new HouseDetailsModel();

            $outProp = $houseDetailsModel->saveHouseDetails( $propertyDetails, $validate = 'false' );

            $availableSpaceDetails = array(
                'house_id'          => $outProp['HouseDetails']['id'],
                'rooms_available'   => isset( $reqData['room_description']['avlbl_rooms'] ) ? $reqData['room_description']['avlbl_rooms'] : '1',
                'is_shared'         => isset( $reqData['room_description']['avlbl_room_is_shared'] ) ? $reqData['room_description']['avlbl_room_is_shared'] : '0',
                'attached_bathroom' => isset( $reqData['room_description']['avlbl_attached_bathroom'] ) ? $reqData['room_description']['avlbl_attached_bathroom'] : '0',
                'bed'               => isset( $reqData['room_description']['avlbl_beds'] ) ? $reqData['room_description']['avlbl_beds'] : '0',
                'balcony'           => isset( $reqData['room_description']['avlbl_balcony'] ) ? $reqData['room_description']['avlbl_balcony'] : '0',
                'rent'              => isset( $reqData['room_description']['avlbl_rent'] ) ? $reqData['room_description']['avlbl_rent'] : '',
                'available_from'    => $availableFrom,
                'security_deposit'  => isset( $reqData['room_description']['avlbl_security_deposit'] ) ? $reqData['room_description']['avlbl_security_deposit'] : ''
            );

            App::uses('HouseSpaceAvailableModel', 'Houses.Model');

            $houseSpaceAvailableModel = new HouseSpaceAvailableModel();

            $outAvlbl = $houseSpaceAvailableModel->saveHouseSpaceAvailable( $availableSpaceDetails, $validate = 'false' );

            $ownerPreferencesDetails = array(
                'house_id'          => $outProp['HouseDetails']['id'],
                'profession'        => isset( $reqData['property_prefrences']['pref_occupation'] ) ? $reqData['property_prefrences']['pref_occupation'] : '3',
                'food_pref'         => isset( $reqData['property_prefrences']['pref_food'] ) ? $reqData['property_prefrences']['pref_food'] : '',
                'smoking'           => isset( $reqData['property_prefrences']['pref_smok_drink'] ) ? $reqData['property_prefrences']['pref_smok_drink'] : '',
                'drinking'          => isset( $reqData['property_prefrences']['pref_smok_drink'] ) ? $reqData['property_prefrences']['pref_smok_drink'] : '',
                'guests'            => isset( $reqData['property_prefrences']['pref_guests'] ) ? $reqData['property_prefrences']['pref_guests'] : '',
                'pets'              => isset( $reqData['property_prefrences']['pref_pets'] ) ? $reqData['property_prefrences']['pref_pets'] : '',
                'gender'            => isset( $reqData['property_prefrences']['pref_gender'] ) ? $reqData['property_prefrences']['pref_gender'] : ''
            );

            App::uses('OwnerPreferencesModel', 'Houses.Model');

            $ownerPreferencesModel = new OwnerPreferencesModel();

            $outPref = $ownerPreferencesModel->saveOwnerPreferences( $ownerPreferencesDetails, $validate = 'false' );

            $userDetails = array(
                'id'                => isset( $reqData['user_details']['user_id'] ) ? $reqData['user_details']['user_id'] : '',
                'is_owner'          => '1'
            );

            App::uses('UsersModel', 'Users.Model');

            $obj = new UsersModel();

            $outUsers = $obj->saveUsers( $userDetails, $validate = 'true' );

            $returnValue = array(
                'house_id' => $outProp['HouseDetails']['id']
            );

            return $returnValue;
        }
        catch( Exception $e )
        {
            throw $e;
        }
    }
}