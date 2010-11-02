<?php
/**
 * File containing the ezpResource class.
 *
 * @package
 * @version //autogen//
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 *
 * <code>
 * </code>
 *
 * @package
 * @version //autogen//
 */

class ezpResource
{
    /**
     Finds an appropriate resource in the filesystem with name $name by using the override
     system.

     \param string $name
     \param string $subdir The subdirectory to look inside, relative to design folder
     \param bool   $skipSlash If true then the leading slash is skipped from the returned value.
     */
    static public function find( $name, $subdir = false, $skipSlash = false )
    {
        $sys = eZSys::instance();
        if ( $skipSlash && strlen( $sys->wwwDir() ) != 0 )
        {
            $skipSlash = false;
        }

        $bases = eZTemplateDesignResource::allDesignBases();
        $triedFiles = array();
        $fileInfo = eZTemplateDesignResource::fileMatch( $bases, $subdir, $name, $triedFiles );

        if ( !$fileInfo )
        {
            throw new ezpResourceError( "Design element $name does not exist in any design",
                                        $name, $triedFiles );
        }
        else
        {
            $filePath = $fileInfo['path'];
        }

        if ( $skipSlash )
        {
            return $filePath;
        }
        return $sys->wwwDir() . '/' . $filePath;
    }

}

?>
