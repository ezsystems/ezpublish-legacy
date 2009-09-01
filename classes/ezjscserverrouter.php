<?php
//
// Definition of ezjscServerRouter class
//
// Created on: <1-Jul-2008 12:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*
  Perfoms calls to custom functions or templates depending on arguments and ini settings 
*/


class ezjscServerRouter
{
    protected $className = null;
    protected $functionName = null;
    protected $functionArguments = array();
    protected $isTemplateFunction = false;

    protected function ezjscServerRouter( $className, $functionName = 'call', $functionArguments = array(), $isTemplateFunction = false )
    {
        $this->className = $className;
        $this->functionName = $functionName;
        $this->functionArguments = $functionArguments;
        $this->isTemplateFunction = $isTemplateFunction;
    }

    /**
     * Gets instance of ezjscServerRouter, IF arguments validates and user has access
     *
     * @param array $arguments
     * @param bool $requireIniGroupe Make sure this is true if $arguments comes from user input
     * @param bool $checkFunctionExistence
     * @return ezjscServerRouter|null
     */
    public static function getInstance( $arguments, $requireIniGroupe = true, $checkFunctionExistence = false )
    {
        if ( !is_array( $arguments ) || count( $arguments ) < 2 )
        {
            // returns null if argumenst are invalid
            return null;   
        }

        $className = $callClassName = array_shift( $arguments );
        $functionName = array_shift( $arguments );
        $isTemplateFunction = false;
        $permissionFunctions = false;
        $permissionPrFunction = false;
        $ezjscoreIni = eZINI::instance( 'ezjscore.ini' );

        if ( $ezjscoreIni->hasGroup( 'ezjscServer_' . $callClassName ) )
        {
           // load file if defined, else use autoload 
           if ( $ezjscoreIni->hasVariable( 'ezjscServer_' . $callClassName, 'File' ) )
                include_once( $ezjscoreIni->variable( 'ezjscServer_' . $callClassName, 'File' ) );

           if ( $ezjscoreIni->hasVariable( 'ezjscServer_' . $callClassName, 'TemplateFunction' ) )
                $isTemplateFunction = $ezjscoreIni->variable( 'ezjscServer_' . $callClassName, 'TemplateFunction' ) === 'true';

           // check permissions
           if ( $ezjscoreIni->hasVariable( 'ezjscServer_' . $callClassName, 'Functions' ) )
                $permissionFunctions = $ezjscoreIni->variable( 'ezjscServer_' . $callClassName, 'Functions' );

           // check permissions
           if ( $ezjscoreIni->hasVariable( 'ezjscServer_' . $callClassName, 'PermissionPrFunction' ) )
                $permissionPrFunction = $ezjscoreIni->variable( 'ezjscServer_' . $callClassName, 'PermissionPrFunction' ) === 'enabled';

           // get class name if defined, else use first argument as class name
           if ( $ezjscoreIni->hasVariable( 'ezjscServer_' . $callClassName, 'Class' ) )
                $className = $ezjscoreIni->variable( 'ezjscServer_' . $callClassName, 'Class' );
        }
        else if ( $requireIniGroupe )
        {
            // return null if ini is not defined as a safty messure
            // to avoid letting user call all eZ Publish classes
            echo '/* 1 */';
            return null;
        }

        if ( $checkFunctionExistence && !self::staticHasFunction( $className, $functionName, $isTemplateFunction  ) )
        {
            echo '/* 2 */';
            return null;
        }

        if ( $permissionFunctions !== false )
        {
        	if ( !self::hasAccess( $permissionFunctions, ( $permissionPrFunction ? $functionName : null ) ) )
        	{
        	    echo '/* 3 */';
        	    return null;
        	}
        }

        return new ezjscServerRouter( $className, $functionName, $arguments, $isTemplateFunction );
    }

    /**
     * Gets the name of the current class+function
     *
     * @return string
     */
    public function getName()
    {
        return $this->className . '::' . $this->functionName;
    }

    /**
     * Gets the cache time ( modified time ) for use when chaching the response.
     *
     * @param array $environmentArguments Optionall hash of environment variables
     * @return int
     */
    public function getCacheTime( $environmentArguments = array()  )
    {
        if ( $this->isTemplateFunction )
        {
            return 0;
        }
        else if ( method_exists( $this->className, 'getCacheTime' ) )
        {
            return call_user_func( array( $this->className, 'getCacheTime' ), $this->functionName );
        }
        else
        {
            return 0;
        }
    }

    /**
     * Checks if current user has access based on $requiredFunctions
     *
     * @param array $requiredFunctions
     * @param null|string $functionName
     * @return bool
     */
    public static function hasAccess( $requiredFunctions, $functionName = null )
    {
    	$currentUser = eZUser::currentUser();
    	$accessResult = $currentUser->hasAccessTo( 'ezjscore', 'call' );
        if ( $accessResult[ 'accessWord' ] === 'yes'  )
        {
            return true;
        }
        else if ( $accessResult[ 'accessWord' ] === 'no'  )
        {
            return false;
        }

        // Rest is $accessResult[ 'accessWord' ] === 'limited'
        $functionName = $functionName !== null ? '_' . $functionName : '';
    	$ezjscoreIni = eZINI::instance( 'ezjscore.ini' );
        $ezjscoreFunctionList = $ezjscoreIni->variable( 'ezjscServer', 'FunctionList' );
        foreach( $requiredFunctions as $requiredFunction )
        {
        	$permissionName = $requiredFunction . $functionName;
        	if ( !in_array( $permissionName, $ezjscoreFunctionList ) )
        	{
        		eZDebug::writeWarning( "'$permissionName' is not defined in ezjscore.ini[ezjscServer]FunctionList", __METHOD__ );
        		return false;
        	}

             // Something with $accessResult
             foreach ( $accessResult['policies'] as $pkey => $limitationArray  )
             {
                if ( isset( $limitationArray['FunctionList'] ) )
                {
                    if ( !in_array( $permissionName, $limitationArray['FunctionList'] ) )
                        return false;
                }
             }
        }
        return true;
    }

    /**
     * Checks if function actually exits on the requested ezjscServerFunctions
     *
     * @return bool
     */
    public function hasFunction()
    {
        return self::staticHasFunction( $this->className, $this->functionName, $this->isTemplateFunction  );
    }

    /**
     * Checks if function actually exits on the requested ezjscServerFunctions
     *
     * @return bool
     */
    public static function staticHasFunction( $className, $functionName, $isTemplateFunction = false )
    {
        if ( $isTemplateFunction )
        {
            return true;//todo: find a way to look for templates
        }
        else
        {
            return method_exists( $className, $functionName );
        }
    }

    /**
     * Call the defined function on requested ezjscServerFunctions class
     *
     * @param array $environmentArguments
     * @return mixed
     */
    public function call( &$environmentArguments = array(), $isPackeStage = false  )
    {
        if ( $this->isTemplateFunction )
        {
            include_once( 'kernel/common/template.php' );
            $tpl = templateInit();
            $tpl->setVariable( 'arguments', $this->functionArguments );
            $tpl->setVariable( 'environment', $environmentArguments );
            return $tpl->fetch( 'design:' . $this->className . '/' . $this->functionName . '.tpl' );
        }
        else
        {
            return call_user_func_array( array( $this->className, $this->functionName ), array( $this->functionArguments, &$environmentArguments, $isPackeStage ) );
        }
    }
    
}

?>