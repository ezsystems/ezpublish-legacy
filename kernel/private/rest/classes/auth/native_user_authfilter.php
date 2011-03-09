<?php
/**
 * File containing ezpNativeUserAuthFilter class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
class ezpNativeUserAuthFilter extends ezcAuthenticationFilter
{
    const STATUS_INVALID_USER = 1;

    /**
     * Will check if UserID provided in credentials is valid
     * @see ezcAuthenticationFilter::run()
     */
    public function run( $credentials )
    {
        $status = self::STATUS_INVALID_USER;
        $count = eZPersistentObject::count( eZUser::definition(), array( 'contentobject_id' => (int)$credentials->id ) );
        if ( $count > 0 )
        {
            $status = self::STATUS_OK;
        }

        return $status;
    }
}
?>