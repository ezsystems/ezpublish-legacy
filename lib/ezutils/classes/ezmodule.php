<?php
//
// Definition of eZModule class
//
// Created on: <17-Apr-2002 11:11:39 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/**
 * The eZModule class is used to instanciate and use modules & views.
 * 
 * Loading the "content" module, and running the "history" view
 * <code>
 * <?php
 * $contentModule = eZModule::findModule( 'content' );
 * $result = $contentModule->run( 'history', array( 1 ) );
 * ?>
 * </code>
 * 
 * Running the CopyVersion action of the content/history view:
 * <code>
 * <?php
 * $contentModule = eZModule::findModule( 'content' );
 * $contentModule->setCurrentView( 'history' );
 * $contentModule->setCurrentAction( 'CopyVersion' );
 * // we will copy version 3
 * $contentModule->setActionParameter( 'VersionID', 3 );
 * $contentModule->run( 'history', array( 20 ) );
 * ?>
 * </code>
 */

class eZModule
{
    /**
     * Module execution status: IDLE
     * @var int
     */    
    const STATUS_IDLE = 0;
    
    /**
     * Module execution status: OK
     * @var int
     */    
    const STATUS_OK = 1;
    
    /**
     * Module execution status: FAILED
     * @var int
     */    
    const STATUS_FAILED = 2;
    
    /**
     * Module execution status: REDIRECT
     * @var int
     */    
    const STATUS_REDIRECT = 3;

    /**
     * Module execution status: RERUN
     * @var int
     */    
    const STATUS_RERUN = 4;

    /**
     * Hooks execution status: OK
     * @var int
     */
    const HOOK_STATUS_OK = 0;

    /**
     * Hooks execution status: CANCEL_RUN
     * @var int
     */
    const HOOK_STATUS_CANCEL_RUN = 1;

    /**
     * Hooks execution status: FAILED
     * @var int
     */
    const HOOK_STATUS_FAILED = 2;

    /**
     * Constructor. Initializes the module.
     * 
     * @param string $path
     *        Relative path to the module, without the module name
     * @param string $file
     *        Relative path to the module definition file module.php
     * @param string $moduleName
     * @param boolean $checkFileExistence
     *        Always set to false in the current code base, since the check is
     *        usually performed before the constructor is called
     */
    function eZModule( $path, $file, $moduleName, $checkFileExistence = true )
    {
        $this->initialize( $path, $file, $moduleName, $checkFileExistence);
    }

    /**
     * Initializes the module object.
     * 
     * @param string $path
     *        Directory where the module is declared, without the modulename
     *        component
     * @param string $file
     *        Full (relative) path to the module.php file describing the module
     * @param string $moduleName
     *        The module name (content, user...)
     * @param bool $checkFileExistence
     *        Wether or not $file's existence should be checked
     * 
     * @todo Check if it can be marked as private
     * @return void
     */
    function initialize( $path, $file, $moduleName, $checkFileExistence = true )
    {
        if ( $checkFileExistence === false || file_exists( $file ) )
        {
            unset( $FunctionList );
            unset( $Module );
            unset( $ViewList );
            include( $file );
            $this->Functions = $ViewList;
            if ( isset( $FunctionList ) and
                 is_array( $FunctionList ) and
                 count( $FunctionList ) > 0 )
            {
                ksort( $FunctionList, SORT_STRING );
                $this->FunctionList = $FunctionList;
            }
            else
            {
                $this->FunctionList = array();
            }
            if ( empty( $Module ) )
            {
                $Module = array( "name" => "null",
                                 "variable_params" => false,
                                 "function" => array() );
            }
            $this->Module = $Module;
            $this->Name = $moduleName;
            $this->Path = $path;
            $this->Title = "";
            $this->UIContext = 'navigation';
            $this->UIComponent = $moduleName;

            $uiComponentMatch = 'module';
            if ( isset( $this->Module['ui_component_match'] ) )
            {
                $uiComponentMatch = $this->Module['ui_component_match'];
            }
            $this->UIComponentMatch = $uiComponentMatch;

            foreach( $this->Functions as $key => $dummy)
            {
                $this->Functions[$key]["uri"] = "/$moduleName/$key";
            }
        }
        else
        {
            $this->Functions = array();
            $this->Module = array( "name" => "null",
                                   "variable_params" => false,
                                   "function" => array() );
            $this->Name = $moduleName;
            $this->Path = $path;
            $this->Title = "";
            $this->UIContext = 'navigation';
            $this->UIComponent = $moduleName;
            $this->UIComponentMatch = 'module';
        }
        $this->HookList = array();
        $this->ExitStatus = eZModule::STATUS_IDLE;
        $this->ErrorCode = 0;
        $this->ViewActions = array();
        $this->OriginalParameters = null;
        $this->UserParameters = array();

        // Load in navigation part overrides
        $ini = eZINI::instance( 'module.ini' );
        $this->NavigationParts = $ini->variable( 'ModuleOverrides', 'NavigationPart' );
    }

    /**
     * Returns the module's URI (/content, /user...)
     * @return string The module's URI
     * 
     * @see functionURI()
     */
    function uri()
    {
        return "/" . $this->Name;
    }

    /**
     * Returns the URI to a module's function
     * 
     * @param string $function The function to return the URI for
     * @return string|null
     *         - the function's URI (content/edit, user/login, etc)
     *         - if $function is empty or the module is a singleView one,
     *           the module's uri (content/, user/...)
     *         - null if the function's not found
     * 
     * @see uri()
     */
    function functionURI( $function )
    {
        if ( $this->singleFunction() or
             $function == '' )
            return $this->uri();
        if ( isset( $this->Functions[$function] ) )
            return $this->Functions[$function]["uri"];
        else
            return null;
    }

    /**
     * Returns the title of the last ran view. Normally set by the view itself,
     * and displayed as the page's title
     * 
     * @return string
     * 
     * @see setTitle()
     */
    function title()
    {
        return $this->Title;
    }

    /**
     * Sets the current view for the module to \a $title.
     * 
     * @param string $title The title to be set
     * 
     * @see title()
     */
    function setTitle( $title )
    {
        $this->Title = $title;
    }

    /**
     * Sets the name of the currently running module. The URIs will be updated
     * accordingly
     * 
     * @param string $name The name to be set
     * 
     * @return void
     * 
     * @see uri(), functionURI()
     */
    function setCurrentName( $name )
    {
        $this->Name = $name;
        foreach( $this->Functions as $key => $dummy )
        {
            $this->Functions[$key]["uri"] = "/$name/$key";
        }
    }

    /**
     * Sets the currently executed view
     * 
     * @param string $name The view name
     * 
     * @return void
     * 
     * @see currentView()
    */
    function setCurrentView( $name )
    {
        $GLOBALS['eZModuleCurrentView'] = $name;
    }

    /**
     * Checks if the module is a single view one
     * @return bool
     */
    function singleFunction()
    {
        return count( $this->Functions ) == 0;
    }

    /**
     * Returns the UI context
     * @return string The current UI context. Default: 'navigation'
     * 
     * @see setUIContextName()
     */
    function uiContextName()
    {
        return $this->UIContext;
    }

    /**
     * Returns the UI component, by default the module name
     * 
     * @return string The current UI component
     * 
     * @see setUIComponentName()
     */
    function uiComponentName()
    {
        return $this->UIComponent;
    }

    /**
     * Sets the current context
     * 
     * @param string $context The new context string
     * 
     * @see uiContextName()
     * 
     * @return void
     */
    function setUIContextName( $context )
    {
        $this->UIContext = $context;
    }

    /**
     * Sets the current component name
     * 
     * @param string $component The new component name
     * 
     * @see uiComponentName()
     * 
     * @return void
     */
    function setUIComponentName( $component )
    {
        $this->UIComponent = $component;
    }

    /**
     * Returns the last exit status after a view has been executed
     * 
     * @return int one of STATUS_* constants
     * 
     * @see setExitStatus()
     */
    function exitStatus()
    {
        return $this->ExitStatus;
    }

    /**
     * Sets the exit status. This status will be used to inform the user,
     * perform a redirection...
     * 
     * @param int $stat One of the eZModule::STATUS_* constants
     * 
     * @see exitStatus()
     * @return void
     */
    function setExitStatus( $stat )
    {
        $this->ExitStatus = $stat;
    }

    /**
     * Returns the last error code. An error should only be returned if the
     * module's status is eZModule::STATUS_FAILED
     * 
     * @return int The error code, or 0 if no error occured
     * 
     * @see setErrorCode(), exitStatus(), setExitStatus()
     */
    function errorCode()
    {
        return $this->ErrorCode;
    }

    /**
     * Sets the current error code.
     * @note For the error code to be used, the module's status needs to be set
     *       to eZModule::STATUS_FAILED
     * @see setExitStatus(), errorCode()
     * @return void
     */
    function setErrorCode( $errorCode )
    {
        $this->ErrorCode = $errorCode;
    }

    /**
     * Returns the error module which will be ran if an error occurs
     * 
     * @return array the error module name (keys: module, view)
     * 
     * @see handleError()
     */
    function errorModule()
    {
        if ( !isset( $GLOBALS['eZModuleGlobalErrorModule'] ) )
            $GLOBALS['eZModuleGlobalErrorModule'] = array( 'module' => 'error',
                                        'view' => 'view' );
        return $GLOBALS['eZModuleGlobalErrorModule'];
    }

    /**
     * Sets the module to be used to handle errors
     * 
     * @param string $moduleName
     * @param string $viewName
     * 
     * @see handleError(), errorModule()
     */
    function setErrorModule( $moduleName, $viewName )
    {
        $GLOBALS['eZModuleGlobalErrorModule'] = array( 'module' => $moduleName,
                                                       'view' => $viewName );
    }

    /**
     * Runs the defined error module
     * Sets the state of the module object to \c failed and sets the error code.
     * 
     * @param mixed $errorCode
     * @param mixed $errorType
     * @param array $parameters
     * @param mixed $userParameters
     * 
     * @see setErrorModule(), errorModule()
     */
    function handleError( $errorCode, $errorType = false, $parameters = array(), $userParameters = false )
    {
        if ( !$errorType )
        {
            eZDebug::writeWarning( "No error type specified for error code $errorCode, assuming kernel.\nA specific error type should be supplied, please check your code.",
                                   'eZModule::handleError' );
            $errorType = 'kernel';
        }
        // @todo Make this non static
        $errorModule = eZModule::errorModule();
        
        // @todo Does this need to be static ?
        $module = eZModule::findModule( $errorModule['module'], $this );

        if ( $module === null )
        {
            return false;
        }

        $result = $module->run( $errorModule['view'], array( $errorType, $errorCode, $parameters, $userParameters ) );
        // The error module may want to redirect to another URL, see error.ini
        if ( $this->exitStatus() != eZModule::STATUS_REDIRECT and
             $this->exitStatus() != eZModule::STATUS_RERUN )
        {
            $this->setExitStatus( eZModule::STATUS_FAILED );
            $this->setErrorCode( $errorCode );
        }
        return $result;
    }

    /**
     * Redirects to another module / view
     * 
     * @note Use redirectModule() If the target module object is already available
     * @note Use redirectToView() if you want to redirect to another view in the same module
     * 
     * @see redirectionURI(), redirectModule(), redirectToView()
     * 
     * @param string $moduleName Target module name
     * @param string $viewName Target view name
     * @param array $parameters View parameters array
     * @param array $unorderedParameters Unordered parameters array
     * @param array $userParameters User parameters array
     * @param string $anchor Anchor to use in the redirection (prepended to the URL)
     * 
     * @return bool true if the redirection was performed, false if the module wasn't found
     */
    function redirect( $moduleName, $viewName, $parameters = array(),
                       $unorderedParameters = null, $userParameters = false,
                       $anchor = false )
    {
        $module = eZModule::exists( $moduleName );
        if ( $module )
        {
            return $this->redirectModule( $module, $viewName, $parameters,
                                          $unorderedParameters, $userParameters, $anchor );
        }
        else
        {
            eZDebug::writeError( 'Undefined module: ' . $moduleName, 'eZModule::redirect' );
        }
        return false;
    }

    /**
     * Redirects to another view in the current module
     * 
     * @see redirectionURI(), redirectModule(), redirect()
     * 
     * @param string $viewName Target view name
     * @param array $parameters View parameters
     * @param array $unorderedParameters Unordered view parameters
     * @param array $userParameters User parameters
     * @param string $anchor Redirection URI anchor
     * 
     * @return boolean true if successful, false if the view isn't found
     */
    function redirectToView( $viewName = '', $parameters = array(),
                             $unorderedParameters = null, $userParameters = false,
                             $anchor = false )
    {
        return $this->redirectModule( $this, $viewName, $parameters,
                                      $unorderedParameters, $userParameters, $anchor );
    }

    /**
     * Redirects to another module / view.
     * 
     * The difference with redirect is that the $module parameter is an object
     * instead of a string
     * 
     * @param eZModule $moduleName Target module name
     * @param string $viewName Target view name
     * @param array $parameters View parameters array
     * @param array $unorderedParameters Unordered parameters array
     * @param array $userParameters User parameters array
     * @param string $anchor Redirection URI anchor
     * 
     * @return boolean true. Just true.
     * 
     * @todo Deprecate; have redirect() check if $module is an eZModule or a string
     */
    function redirectModule( $module, $viewName, $parameters = array(),
                             $unorderedParameters = null, $userParameters = false,
                             $anchor = false )
    {
        $uri = $this->redirectionURIForModule( $module, $viewName, $parameters,
                                               $unorderedParameters, $userParameters, $anchor );
        $this->redirectTo( $uri );
        return true;
    }

    /**
     * Creates the redirection URI for a given module, view & parameters.
     * Unlike redirectionURIForModule(), the $module parameter is the module name
     * 
     * @param string $moduleName Redirection module name
     * @param string $viewName Redirection view name
     * @param array $parameters View parameters
     * @param array $unorderedParameters Unordered parameters
     * @param array $userParameters User parameters
     * @param string $anchor Redirection URI anchor
     * 
     * @return string|boolean The redirection URI, or false if the module isn't found
     * 
     * @see redirect(), redirectionURIForModule(), redirectToView(), redirectModule()
     */
    function redirectionURI( $moduleName, $viewName, $parameters = array(),
                             $unorderedParameters = null, $userParameters = false,
                             $anchor = false )
    {
        $module = eZModule::exists( $moduleName );
        if ( $module )
        {
            return $this->redirectionURIForModule( $module, $viewName, $parameters,
                                                   $unorderedParameters, $userParameters, $anchor );
        }
        else
            eZDebug::writeError( 'Undefined module: ' . $moduleName, 'eZModule::redirectionURI' );
        return false;
    }

    /**
     * Creates the redirection URI for the current module, view & parameters
     * 
     * @return string The redirection URI
     * 
     * @see redirectionURIForModule()
     */
    function currentRedirectionURI()
    {
        $module = $this;
        $viewName = eZModule::currentView();
        $parameters = $this->OriginalViewParameters;
        $unorderedParameters = $this->OriginalUnorderedParameters;
        $userParameters = $this->UserParameters;
        return $this->redirectionURIForModule( $module, $viewName, $parameters,
                                               $unorderedParameters, $userParameters );
    }

    /**
     * Redirects to the current module and view, it will use currentRedirectionURI() to
     * figure out the URL.
     * 
     * @note By changing using setCurrentName() and setCurrentView() first it is
     *       possible to redirect to another module or view with the same
     *       parameters.
     * 
     * @see currentRedirectionURI(), redirectTo()
     * 
     * @return void
     */
    function redirectCurrent()
    {
        $this->redirectTo( $this->currentRedirectionURI() );
    }

    /**
     * Creates the redirection URI for a given module, view & parameters.
     * Unlike redirectionURI(), the $module parameter is a module object
     * 
     * @param string $moduleName Redirection module name
     * @param string $viewName
     *        Redirection view name. If empty, the current view will be used
     * @param array $parameters View parameters
     * @param array $unorderedParameters Unordered parameters
     * @param array $userParameters User parameters
     * @param string $anchor Redirection URI anchor
     * 
     * @return string|boolean The redirection URI, or false if the module isn't found
     * 
     * @see redirect(), redirectionURIForModule(), redirectToView(), redirectModule()
     */
    function redirectionURIForModule( $module, $viewName, $parameters = array(),
                                      $unorderedParameters = null, $userParameters = false,
                                      $anchor = false )
    {
        if ( $viewName == '' )
            $viewName = eZModule::currentView();
        $uri = $module->functionURI( $viewName );
        $uri .= '/';
        $viewParameters = $module->parameters( $viewName );
        $parameterIndex = 0;
        $unorderedURI = '';
        $hasUnorderedParameter = false;
        if ( $unorderedParameters !== null )
        {
            $unorderedViewParameters = $module->unorderedParameters( $viewName );
            if ( is_array( $unorderedViewParameters ) )
            {
                foreach ( $unorderedViewParameters as $unorderedViewParameterName => $unorderedViewParameterVariable )
                {
                    if ( isset( $unorderedParameters[$unorderedViewParameterVariable] ) )
                    {
                        $unorderedURI .= $unorderedViewParameterName . '/' . $unorderedParameters[$unorderedViewParameterVariable] . '/';
                        $hasUnorderedParameter = true;
                    }
                }
            }
        }

        if( !isset( $viewParameters ) )
            $viewParameters = array(); // prevent PHP warning below

        foreach ( $viewParameters as $viewParameter )
        {
            if ( !isset( $parameters[$parameterIndex] ) )
            {
                // We don't show a warning anymore since some parameters can be optional
                // In future versions we will need required and optional parameters
                // for modules and give warnings for required ones.
//                 eZDebug::writeWarning( "Missing parameter(s) " . implode( ', ', array_slice( $viewParameters, $parameterIndex ) ) .
//                                        " in view '$viewName'", 'eZModule::redirect' );
            }
            else
                $uri .= $parameters[$parameterIndex] . '/';
            ++$parameterIndex;
        }
        if ( $hasUnorderedParameter )
        {
            $uri .= $unorderedURI;
        }

        if ( is_array( $userParameters ) )
        {
            foreach ( $userParameters as $name => $value )
            {
                $uri .= '/(' . $name . ')/' . $value;
            }
        }

        $uri = preg_replace( "#(^.*)(//+)$#", "\$1", $uri );
        if ( $anchor !== false )
            $uri .= '#' . urlencode( $anchor );
        return $uri;
    }

    /**
     * Returns the defined parameter for a view.
     * 
     * @param string $viewName
     *        The view to get parameters for. If not specified, the current view
     *        is used
     *
     * @return array The parameters definition
     * @see unorderedParameters(), viewData(), currentView(), currentModule()
    */
    function parameters( $viewName = '' )
    {
        if ( $viewName == '' )
            $viewName = eZModule::currentView();
        $viewData = $this->viewData( $viewName );
        if ( isset( $viewData['params'] ) )
        {
            return $viewData['params'];
        }
        return null;
    }

    /**
     * Returns the unordered parameters definition.
     * 
     * @param string $viewName
     *        The view to return parameters for. If npt specified, the current
     *        view is used
     * 
     * @return the unordered parameter definition for the requested view
     * 
     * @see parameters(), viewData(), currentView(), currentModule()
     */
    function unorderedParameters( $viewName = '' )
    {
        if ( $viewName == '' )
            $viewName = eZModule::currentView();
        $viewData = $this->viewData( $viewName );
        if ( isset( $viewData['unordered_params'] ) )
        {
            return $viewData['unordered_params'];
        }
        return null;
    }

    /**
     * Returns data for a view
     * 
     * @param string $viewName
     *        The view to return data for. If omited, the current view is used
     * @see parameters(), unorderedParameters(), currentView(), currentModule()
     * 
     * @return array
     */
    function viewData( $viewName = '' )
    {
        if ( $viewName == '' )
            $viewName = eZModule::currentView();
        if ( $this->singleFunction() )
            $viewData = $this->Module["function"];
        else
            $viewData = $this->Functions[$viewName];
        return $viewData;
    }

    /**
     * Sets the module to redirect at the end of the execution
     * 
     * @param string $uri the URI to redirect to
     * 
     * @see setRedirectURI(), setExitStatus()
     * 
     * @return void
     */
    function redirectTo( $uri )
    {
        $originalURI = $uri;
        $uri = preg_replace( "#(^.*)(/+)$#", "\$1", $uri );
        if ( strlen( $originalURI ) != 0 and
             strlen( $uri ) == 0 )
            $uri = '/';
        $this->RedirectURI = $uri;
        $this->setExitStatus( eZModule::STATUS_REDIRECT );
    }

    /**
     * Returns the current redirection URI
     * 
     * @return string
     * 
     * @see setRedirectURI()
     */
    function redirectURI()
    {
        return $this->RedirectURI;
    }

    /**
     * Sets the URI which will be redirected to when the function exits
     * 
     * @param string $uri The redirection URI
     * 
     * @deprecated 4.3 Not used ANYWHERE in the kernel
     * 
     * @return void
     **/
    function setRedirectURI( $uri )
    {
        $this->RedirectURI = $uri;
    }

    /**
     * Returns the redirection HTTP status (!)
     * 
     * @return something (probably)
     * 
     * @deprecated 4.3 Not used ANYWHERE in the kernel
     */
    function redirectStatus()
    {
        return $this->RedirectStatus;
    }

    /**
     * Sets the HTTP status which will be set when redirecting
     * 
     * @param string $status HTTP status
     * 
     * @note The status must be a valid HTTP status with number and text.
     * 
     * @deprecated 4.3 not used anywyere
     */
    function setRedirectStatus( $status )
    {
        $this->RedirectStatus = $status;
    }

    /**
     * Returns the defined object attributes (as in persistent objects)
     * 
     * @return array the persistent object attributes
     */
    function attributes()
    {
        return array( "uri",
                      "functions",
                      'views',
                      "name",
                      "path",
                      "info",
                      "aviable_functions",
                      "available_functions" );
    }

    /**
     * Checks if an attribute exists
     * 
     * @param string $attr Attribute name
     * 
     * @return bool True if the attribute exists, false otherwise
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /**
     * Returns the value of an attribute
     * 
     * @param string $attr Attribute name
     * 
     * @return mixed The attribute value. If the attribute doesn't exist, a 
     *               warning is thrown, and false is returned
     */
    function attribute( $attr )
    {
        switch( $attr )
        {
            case "uri":
                return $this->uri();
                break;
            case "functions":
                return $this->Functions;
            case "views":
                return $this->Functions;
            case "name":
                return $this->Name;
            case "path":
                return $this->Path;
            case "info":
                return $this->Module;
            case 'aviable_functions':
            case 'available_functions':
                return $this->FunctionList;
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZModule::attribute' );
                return null;
            }
            break;
        }
    }

    /**
     * Sets the current action for a view
     * 
     * @param string $actionName The action to make current
     * @param string $view
     *        The view to set the action for. If omited, the current view is used
     * 
     * @return void
     * 
     * @see currentAction(), isCurrentAction()
    */
    function setCurrentAction( $actionName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( $view == '' or $actionName == '' )
            return false;
        $this->ViewActions[$view] = $actionName;
    }

    /**
     * Returns the current action name.
     * 
     * If the current action is not yet determined it will use the definitions in
     * module.php in order to find out the current action. It first looks trough
     * the \c single_post_actions array in the selected view mode, the key to
     * each element is the name of the post-variable to match, if it matches the
     * element value is set as the action.
     * \code
     * 'single_post_actions' => array( 'PreviewButton' => 'Preview',
     *                                 'PublishButton' => 'Publish' )
     * \endcode
     * If none of these matches it will use the elements from the \c post_actions
     * array to find a match. It uses the element value for each element to match
     * agains a post-variable, if it is found the contents of the post-variable
     * is set as the action.
     * \code
     * 'post_actions' => array( 'BrowseActionName' )
     * \endcode
     *
     * @return string The current action, or false if not set nor found
     * 
     * @see setCurrentAction(), isCurrentAction()
     */
    function currentAction( $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActions[$view] ) )
            return $this->ViewActions[$view];
        $http = eZHTTPTool::instance();
        if ( isset( $this->Functions[$view]['default_action'] ) )
        {
            $defaultAction = $this->Functions[$view]['default_action'];
            foreach ( $defaultAction as $defaultActionStructure )
            {
                $actionName = $defaultActionStructure['name'];
                $type = $defaultActionStructure['type'];
                if ( $type == 'post' )
                {
                    $parameters = array();
                    if ( isset( $defaultActionStructure['parameters'] ) )
                        $parameters = $defaultActionStructure['parameters'];
                    $hasParameters = true;
                    foreach ( $parameters as $parameterName )
                    {
                        if ( !$http->hasPostVariable( $parameterName ) )
                        {
                            $hasParameters = false;
                            break;
                        }
                    }
                    if ( $hasParameters )
                    {
                        $this->ViewActions[$view] = $actionName;
                        return $this->ViewActions[$view];
                    }
                }
                else
                {
                    eZDebug::writeWarning( 'Unknown default action type: ' . $type, 'eZModule::currentAction' );
                }
            }
        }
        if ( isset( $this->Functions[$view]['single_post_actions'] ) )
        {
            $singlePostActions = $this->Functions[$view]['single_post_actions'];
            foreach( $singlePostActions as $postActionName => $realActionName )
            {
                if ( $http->hasPostVariable( $postActionName ) )
                {
                    $this->ViewActions[$view] = $realActionName;
                    return $this->ViewActions[$view];
                }
            }
        }
        if ( isset( $this->Functions[$view]['post_actions'] ) )
        {
            $postActions = $this->Functions[$view]['post_actions'];
            foreach( $postActions as $postActionName )
            {
                if ( $http->hasPostVariable( $postActionName ) )
                {
                    $this->ViewActions[$view] = $http->postVariable( $postActionName );
                    return $this->ViewActions[$view];
                }
            }
        }

        $this->ViewActions[$view] = false;
        return false;
    }

    /**
     * Sets an action parameter value
     * 
     * @param string $parameterName
     * @param mixed $parameterValue
     * @param string $view
     *        The view to set the action parameter for. If omited, the current
     *        view is used
     * @return void
     * @see actionParameter(), hasActionParameter()
     */
    function setActionParameter( $parameterName, $parameterValue, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        $this->ViewActionParameters[$view][$parameterName] = $parameterValue;
    }

    /**
     * Returns an action parameter value
     * 
     * @param string $parameterName
     * @param string $view
     *        The view to return the parameter for. If omited, uses the current view
     * 
     * @return mixed The parameter value, or null + error if not found
     * @see setActionParameter(), hasActionParameter()
     */
    function actionParameter( $parameterName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActionParameters[$view][$parameterName] ) )
            return $this->ViewActionParameters[$view][$parameterName];
        $currentAction = $this->currentAction( $view );
        $http = eZHTTPTool::instance();
        if ( isset( $this->Functions[$view]['post_action_parameters'][$currentAction] ) )
        {
            $postParameters = $this->Functions[$view]['post_action_parameters'][$currentAction];
            if ( isset( $postParameters[$parameterName] ) &&
                 $http->hasPostVariable( $postParameters[$parameterName] ) )
            {
                return $http->postVariable( $postParameters[$parameterName] );
            }
            eZDebug::writeError( "No such action parameter: $parameterName", 'eZModule::actionParameter' );
        }
        if ( isset( $this->Functions[$view]['post_value_action_parameters'][$currentAction] ) )
        {
            $postParameters = $this->Functions[$view]['post_value_action_parameters'][$currentAction];
            if ( isset( $postParameters[$parameterName] ) )
            {
                $postVariables = $http->attribute( 'post' );
                $postVariableNameMatch = $postParameters[$parameterName];
                $regMatch = "/^" . $postVariableNameMatch . "_(.+)$/";
                foreach ( $postVariables as $postVariableName => $postVariableValue )
                {
                    if ( preg_match( $regMatch, $postVariableName, $matches ) )
                    {
                        $parameterValue = $matches[1];
                        $this->ViewActionParameters[$view][$parameterName] = $parameterValue;
                        return $parameterValue;
                    }
                }
                eZDebug::writeError( "No such action parameter: $parameterName", 'eZModule::actionParameter' );
            }
        }
        return null;
    }

    /**
     * Checks if an action parameter is defined for a view
     * 
     * @param string $parameterName
     * @param string $view
     *        The view to check the parameter for. If omited, uses the current view
     * 
     * @return bool
     * 
     * @see setActionParameter(), actionParameter()
     */
    function hasActionParameter( $parameterName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActionParameters[$view][$parameterName] ) )
            return true;
        $currentAction = $this->currentAction( $view );
        $http = eZHTTPTool::instance();
        if ( isset( $this->Functions[$view]['post_action_parameters'][$currentAction] ) )
        {
            $postParameters = $this->Functions[$view]['post_action_parameters'][$currentAction];
            if ( isset( $postParameters[$parameterName] ) and
                 $http->hasPostVariable( $postParameters[$parameterName] ) )
            {
                return true;
            }
        }
        if ( isset( $this->Functions[$view]['post_value_action_parameters'][$currentAction] ) )
        {
            $postParameters = $this->Functions[$view]['post_value_action_parameters'][$currentAction];
            if ( isset( $postParameters[$parameterName] ) )
            {
                $postVariables = $http->attribute( 'post' );
                $postVariableNameMatch = $postParameters[$parameterName];
                $regMatch = "/^" . $postVariableNameMatch . "_(.+)$/";
                foreach ( $postVariables as $postVariableName => $postVariableValue )
                {
                    if ( preg_match( $regMatch, $postVariableName, $matches ) )
                    {
                        $parameterValue = $matches[1];
                        $this->ViewActionParameters[$view][$parameterName] = $parameterValue;
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Checks if the current action is the given one
     * 
     * @param string $actionName The action to check
     * @param string $view The view to check the action for. Current view if omited.
     * 
     * @return bool
     * 
     * @see currentAction(), setCurrentAction()
    */
    function isCurrentAction( $actionName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( $view == '' or $actionName == '' )
            return false;
        return $this->currentAction( $view ) == $actionName;
    }

    /**
     * Adds an entry to a hook. The entry is placed before all other existing
     * entries (LIFO) unless $append is set to true.
     * @param string $hookName
     *        The hook name.
     * @param string $function
     *        Either the name of the function to be run or an array where the
     *        first entry is the object and the second is the method name.
     * @param integer $priority
     *        The hook priority in the hooks stack.
     * @param boolean $expandParameters
     *        Wether or not to expand parameters. If set to true (default), the
     *        parameters will be sent as real function parameters to the hooked
     *        function/method. If set to false, they will be sent as a single
     *        array.
     *        In both cases, the eZModule object will be the first parameter sent
     *        to each hook.
     * @param boolean $append
     *        If set to false (default), the hook will be added at the top of
     *        the hooks list. If set to true, it will be added at the end
     * 
     * @return void
     */
    function addHook( $hookName, $function, $priority = 1, $expandParameters = true, $append = false )
    {
        $hookEntries = isset( $this->HookList[$hookName] ) ? $this->HookList[$hookName] : false;
        if ( !is_array( $hookEntries ) )
        {
            $hookEntries = array();
        }
        $entry = array( 'function' => $function,
                        'expand_parameters' => $expandParameters );

        $position = $priority;
        if ( $append )
        {
            while ( isset( $hookEntries[$position] ) )
                ++$position;
        }
        else
        {
            while ( isset( $hookEntries[$position] ) )
                --$position;
        }
        $this->HookList[$hookName][$position] = $entry;
    }

    /**
     * Runs all hooks found in the hook list named $hookName.
     * 
     * @param string $hookName
     * @param array $parameters
     *        Parameters to provide each function with
     * 
     * @return integer The hook execution status, as one of the eZModule::HOOK_STATUS_*
     *         constants:
     *         - HOOK_STATUS_OK: means that every hook was executed correctly.
     *         - HOOK_STATUS_CANCEL_RUN: execution was cancelled by one hook
     *         - HOOK_STATUS_FAILED: only returned if the last hook failed. In
     *           any case, a warning is thrown.
     *        
     */
    function runHooks( $hookName, $parameters = null )
    {
        $status = null;
        $hookEntries = isset( $this->HookList[$hookName] ) ? $this->HookList[$hookName] : false;
        if ( isset( $hookEntries ) and
             is_array( $hookEntries ) )
        {
            ksort( $hookEntries );
            foreach ( $hookEntries as $hookEntry )
            {
                $function = $hookEntry['function'];
                $expandParameters = $hookEntry['expand_parameters'];
                if ( is_string( $function ) )
                {
                    $functionName = $function;
                    if ( function_exists( $functionName ) )
                    {
                        if ( $parameters === null ||
                             $expandParameters === null )
                        {
                            $retVal = $functionName( $this );
                        }
                        else if ( $expandParameters )
                        {
                            $retVal = call_user_func_array( $functionName, array_merge( array( $this ), $parameters ) );
                        }
                        else
                        {
                            $retVal = $functionName( $this, $parameters );
                        }
                    }
                    else
                    {
                        eZDebug::writeError( "Unknown hook function '$functionName' in hook: $hookName", 'eZModule::runHooks' );
                    }
                }
                else if ( is_array( $function ) )
                {
                    if ( isset( $function[0] ) &&
                         isset( $function[1] ) )
                    {
                        $object = $function[0];
                        $functionName = $function[1];
                        if ( method_exists( $object, $functionName ) )
                        {
                            if ( $parameters === null )
                            {
                                $retVal = $object->$function( $this );
                            }
                            else if ( $expandParameters )
                            {
                                $retVal = call_user_func_array( array( $object, $functionName ), array_merge( array( $this ), $parameters ) );
                            }
                            else
                            {
                                $retVal = $object->$functionName( $this, $parameters );
                            }
                        }
                        else
                        {
                            eZDebug::writeError( "Unknown hook method '$functionName' in class '" . strtolower( get_class( $object ) ) . "' in hook: $hookName", 'eZModule::runHooks' );
                        }
                    }
                    else
                    {
                        eZDebug::writeError( "Missing data for method handling in hook: $hookName", 'eZModule::runHooks' );
                    }
                }
                else
                {
                    eZDebug::writeError( 'Unknown entry type ' . gettype( $function ) . 'in hook: ' . $hookName, 'eZModule::runHooks' );
                }

                switch( $retVal )
                {
                    case eZModule::HOOK_STATUS_OK:
                    {
                    } break;

                    case eZModule::HOOK_STATUS_FAILED:
                    {
                        eZDebug::writeWarning( 'Hook execution failed in hook: ' . $hookName, 'eZModule::runHooks' );
                    } break;

                    case eZModule::HOOK_STATUS_CANCEL_RUN:
                    {
                        return $retVal;
                    } break;
                }
            }
        }
        return $status;
    }

    /**
     * Sets the view result
     * 
     * @param string $result The (usually HTML) view result
     * @param string $view
     *        The view to set the result for. If omited, the current view is used
     * 
     * @return void
     * @see hasViewResult(), viewResult()
     */
    function setViewResult( $result, $view = '' )
    {
        if ( $view == '' )
            $view = $this->currentView();
        $this->ViewResult[$view] = $result;
    }

    /**
     * Checks if a view has a result set
     * 
     * @param string $view The view to test for. If omited, uses the current view
     * @return bool
     */
    function hasViewResult( $view = '' )
    {
        if ( $view == '' )
            $view = $this->currentView();
        return isset( $this->ViewResult[$view] );
    }

    /**
     * Returns the view result
     * 
     * @param string $view
     *        The view to return the result for, or the current one if omited
     * 
     * @return string|null The view result, or null if not set
     */
    function viewResult( $view = '' )
    {
        if ( $view == '' )
            $view = $this->currentView();
        if ( isset( $this->ViewResult[$view] ) )
        {
            return $this->ViewResult[$view];
        }
        return null;
    }

    /**
     * Forwards the current execution to another module/view with the existing
     * parameters.
     * 
     * @param eZModule $module The eZModule object the request will be forwarded to
     * @param string $functionName The function to run in that module
     * @param array $parameters
     *        An array of parameters that will be added to the request. These
     *        will be merged with the existing parameters
     * @return array The forwarded module/view result
     */
    function forward( $module, $functionName, $parameters = false )
    {
        $Return = null;
        if ( $module && $functionName )
        {
            $viewName = eZModule::currentView();

            if ( $parameters === false)
            {
                $parameters = array();
            }

            $parameters = array_merge( $parameters, $this->OriginalViewParameters );
            $unorderedParameters = $this->OriginalUnorderedParameters;
            $userParameters = $this->UserParameters;

            $Return = $module->run( $functionName, $parameters, $unorderedParameters, $userParameters );

            // override default navigation part
            if ( $Return['is_default_navigation_part'] === true )
            {
                if ( $this->singleFunction() )
                    $function = $this->Module["function"];
                else
                    $function = $this->Functions[$functionName];

                if ( isset( $function['default_navigation_part'] ) )
                {
                    $Return['navigation_part'] = $function['default_navigation_part'];
                }
            }

            $this->RedirectURI = $module->redirectURI();
            $this->setExitStatus( $module->exitStatus() );
        }
        return $Return;
    }

    /**
     * Runs a function in the current module
     * 
     * @param string $functionName The function to run
     * @param array $parameters
     *         An indexed list of parameters, these will be mapped onto real
     *         parameters names using the defined parameters names in the
     *         module/function definition.
     *         Any unspecified parameter will be assigned null.
     * @param array $overrideParameters
     *        An asociative array of parameters that will ultimately override
     *        what's in $parameters
     * @param array $userParameters User (custom view) parameters
     *        
     * @return array The run result
     */
    function run( $functionName, $parameters = array(), $overrideParameters = false, $userParameters = false )
    {
        if ( count( $this->Functions ) > 0 and
             !isset( $this->Functions[$functionName] ) )
        {
            eZDebug::writeError( "Undefined view: " . $this->Module["name"] . "::$functionName ",
                                 "eZModule" );
            $this->setExitStatus( eZModule::STATUS_FAILED );
            $Return = null;
            return $Return;
        }
        if ( $this->singleFunction() )
            $function = $this->Module["function"];
        else
            $function = $this->Functions[$functionName];

        $params = array();
        $i = 0;
        $parameterValues = array();
        if ( isset( $function["params"] ) )
        {
            $functionParameterDefinitions = $function["params"];
            foreach ( $functionParameterDefinitions as $param )
            {
                if ( isset( $parameters[$i] ) )
                {
                    $params[$param] = $parameters[$i];
                    $parameterValues[] = $parameters[$i];
                }
                else
                {
                    $params[$param] = null;
                    $parameterValues[] = null;
                }
                ++$i;
            }
        }

        $this->ViewParameters = $parameters;
        $this->OriginalParameters = $parameters;
        $this->OriginalViewParameters = $parameterValues;
        $this->NamedParameters = $params;

        $GLOBALS['eZRequestedModuleParams'] = array( 'module_name' => $this->Name,
                                                     'function_name' => $functionName,
                                                     'parameters' => $params );

        $this->UserParameters = $userParameters;

        if ( isset( $function['ui_context'] ) )
        {
            $this->UIContext = $function['ui_context'];
        }
        if ( isset( $function['ui_component'] ) )
        {
            $this->UIComponent = $function['ui_component'];
        }
        else if ( $this->UIComponentMatch == 'view' )
        {
            $this->UIComponent = $functionName;
        }

        if ( array_key_exists( 'Limitation', $parameters  ) )
        {
            $params['Limitation'] =& $parameters[ 'Limitation' ];
        }

        // check for unordered parameters and initialize variables if they exist
        $unorderedParametersList = array();
        $unorderedParameters = array();
        if ( isset( $function["unordered_params"] ) )
        {
            $unorderedParams = $function["unordered_params"];

            foreach ( $unorderedParams as $urlParamName => $variableParamName )
            {
                if ( in_array( $urlParamName, $parameters ) )
                {
                    $pos = array_search( $urlParamName, $parameters );

                    $params[$variableParamName] = $parameters[$pos + 1];
                    $unorderedParameters[$variableParamName] = $parameters[$pos + 1];
                    $unorderedParametersList[$variableParamName] = $parameters[$pos + 1];
                }
                else
                {
                    $params[$variableParamName] = false;
                    $unorderedParameters[$variableParamName] = false;
                }
            }
        }

        // Loop through user defines parameters
        if ( $userParameters !== false )
        {
            if ( !isset( $params['UserParameters'] ) or
                 !is_array( $params['UserParameters'] ) )
            {
                $params['UserParameters'] = array();
            }

            if ( is_array( $userParameters ) && count( $userParameters ) > 0 )
            {
                foreach ( array_keys( $userParameters ) as $paramKey )
                {
                    if( isset( $function['unordered_params'] ) &&
                        $unorderedParams != null )
                    {
                        if ( array_key_exists( $paramKey, $unorderedParams ) )
                        {
                            $params[$unorderedParams[$paramKey]] = $userParameters[$paramKey];
                            $unorderedParametersList[$unorderedParams[$paramKey]] = $userParameters[$paramKey];
                        }
                    }

                    $params['UserParameters'][$paramKey] = $userParameters[$paramKey];
                }
            }
        }

        $this->OriginalUnorderedParameters = $unorderedParametersList;

        if ( is_array( $overrideParameters ) )
        {
            foreach ( $overrideParameters as $param => $value )
            {
                $params[$param] = $value;
            }
        }
        $params["Module"] = $this;
        $params["ModuleName"] = $this->Name;
        $params["FunctionName"] = $functionName;
        $params["Parameters"] = $parameters;
        $params_as_var = isset( $this->Module["variable_params"] ) ? $this->Module["variable_params"] : false;
        $this->ExitStatus = eZModule::STATUS_OK;
//        eZDebug::writeNotice( $params, 'module parameters1' );


        $currentView =& $GLOBALS['eZModuleCurrentView'];
        $viewStack =& $GLOBALS['eZModuleViewStack'];
        if ( !isset( $currentView ) )
            $currentView = false;
        if ( !isset( $viewStack ) )
            $viewStack = array();
        if ( is_array( $currentView ) )
            $viewStack[] = $currentView;
        $currentView = array( 'view' => $functionName,
                              'module' => $this->Name );
        $Return = eZProcess::run( $this->Path . "/" . $this->Name . "/" . $function["script"],
                                  $params,
                                  $params_as_var );

        if ( $this->hasViewResult( $functionName ) )
        {
            $Return = $this->viewResult( $functionName );
        }

        if ( count( $viewStack ) > 0 )
            $currentView = array_pop( $viewStack );
        else
            $currentView = false;

        // Check if the module has set the navigation part, if not default to module setting
        if ( !isset( $Return['navigation_part'] ) )
        {
            $Return['is_default_navigation_part'] = true;
            if ( isset( $function['default_navigation_part'] ) )
                $Return['navigation_part'] = $function['default_navigation_part'];

        }
        else
        {
            $Return['is_default_navigation_part'] = false;
        }

        // Check if we have overrides for navigation part
        $viewName = $this->Name . '/' . $functionName;
        if ( isset( $this->NavigationParts[$viewName] ) )
        {
            $Return['is_default_navigation_part'] = false;
            $Return['navigation_part'] = $this->NavigationParts[$viewName];
        }
        else if ( isset( $this->NavigationParts[$this->Name] ) )
        {
            $Return['is_default_navigation_part'] = false;
            $Return['navigation_part'] = $this->NavigationParts[$this->Name];
        }

        return $Return;
    }

    /**
     * Returns the current view name
     * 
     * @return string The current view name, or false if not defined
     * 
     * @note This is a system-wide value
     * 
     * @see currentModule(), setCurrentView()
    */
    function currentView()
    {
        $currentView = $GLOBALS['eZModuleCurrentView'];
        if ( $currentView !== false )
            return $currentView['view'];
        return false;
    }

    /**
     * Returns the current module name
     * 
     * @return string the current module name, or false if not set
     * 
     * @note This is a system-wide value
    */
    function currentModule()
    {
        $currentView = $GLOBALS['eZModuleCurrentView'];
        if ( $currentView !== false )
            return $currentView['module'];
        return false;
    }

    /**
     * Returns the search path list for modules
     * 
     * @return array
     * 
     * @see setGlobalPathList(), addGlobalPathList()
    */
    static function globalPathList()
    {
        if ( !isset( $GLOBALS['eZModuleGlobalPathList'] ) )
            return null;
        return $GLOBALS['eZModuleGlobalPathList'];
    }

    /**
     * Returns the list of active module repositories, as defined in module.ini
     * 
     * @param boolean $useExtensions
     *        If true, module.ini files in extensions will be scanned as well.
     *        If false, only the module.ini overrides in settings will be.
     * 
     * @return array a path list of currently active modules
     */
    static function activeModuleRepositories( $useExtensions = true )
    {
        $moduleINI = eZINI::instance( 'module.ini' );
        $moduleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );

        if ( $useExtensions )
        {
            $extensionRepositories = $moduleINI->variable( 'ModuleSettings', 'ExtensionRepositories' );
            $extensionDirectory = eZExtension::baseDirectory();
            $activeExtensions = eZExtension::activeExtensions();
            $globalExtensionRepositories = array();

            foreach ( $extensionRepositories as $extensionRepository )
            {
                $extPath = $extensionDirectory . '/' . $extensionRepository;
                $modulePath = $extPath . '/modules';
                if ( !in_array( $extensionRepository, $activeExtensions ) )
                {
                    eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but has not yet been activated.\n" .
                                           "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions\n" .
                                           "or make sure it is activated in the setting ExtensionSettings/ActiveExtensions in site.ini." );
                }
                else if ( file_exists( $modulePath ) )
                {
                    $globalExtensionRepositories[] = $modulePath;
                }
                else if ( !file_exists( $extPath ) )
                {
                    eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but the extension itself does not exist.\n" .
                                           "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions.\n" .
                                           "You should probably remove this extension from the list." );
                }
                else
                {
                    eZDebug::writeWarning( "Extension '$extensionRepository' does not have the subdirectory 'modules' allthough it reported it had modules.\n" .
                                           "Looked for directory '" . $modulePath . "'\n" .
                                           "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extension." );
                }
            }

            $moduleRepositories = array_merge( $moduleRepositories, $globalExtensionRepositories );
        }

        return $moduleRepositories;
    }

    /**
     * Sets the value of the global path list used to search for modules.
     * @param array|string $pathList
     *        Either an array of path, or a single path as a string
     * @return void
     * @see addGlobalPathList(), globalPathList()
     */
    static function setGlobalPathList( $pathList )
    {
        if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $GLOBALS['eZModuleGlobalPathList'] = $pathList;
    }

    /**
     * Adds a new entry to the global path list
     * @param array|string $pathList
     *        Either an array of path or a single path string
     * @return void
     * @see setGlobalPathList(), globalPathList()
     */
    static function addGlobalPathList( $pathList )
    {
        if ( !is_array( $GLOBALS['eZModuleGlobalPathList'] ) )
        {
            $GLOBALS['eZModuleGlobalPathList'] = array();
        }
        if ( !is_array( $pathList ) )
        {
            $pathList = array( $pathList );
        }
        $GLOBALS['eZModuleGlobalPathList'] = array_merge( $GLOBALS['eZModuleGlobalPathList'], $pathList );
    }

    /**
     * Loads a module object by name
     *
     * @param string $moduleName The name of the module to find (ex: content)
     * @param array|string
     *        Either an array of path or a single path string. These will be
     *        used as additionnal locations that will be looked into
     * @param boolean $showError
     *        If true an error will be shown if the module it not found.
     * @return eZModule The eZModule object, or null if the module wasn't found
     * @see findModule()
     */
    static function exists( $moduleName, $pathList = null, $showError = false )
    {
        $module = null;
        return eZModule::findModule( $moduleName, $module, $pathList, $showError );
    }

    /**
     * Loads a module object by name.
     * The only difference with exists() is that the $module parameter will be
     * assigned the found module.
     *
     * @param string $moduleName The name of the module to find (ex: content)
     * @param mixed $module This parameter will receive the found module object
     * @param array|string
     *        Either an array of path or a single path string. These will be
     *        used as additionnal locations that will be looked into
     * @param boolean $showError
     *        If true an error will be shown if the module it not found.
     * @return eZModule The eZModule object, or null if the module wasn't found
     * @see exists()
     */
    static function findModule( $moduleName, $module = null, $pathList = null, $showError = false )
    {
        if ( $pathList === null )
            $pathList = array();
        else if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $searchPathList = eZModule::globalPathList();
        if ( $searchPathList === null )
            $searchPathList = array();
        $searchPathList = array_merge( $searchPathList, $pathList );
        $triedList = array();
        $triedDirList = array();
        $foundADir = false;
        foreach ( $searchPathList as $path )
        {
            $dir = "$path/$moduleName";
            $file = "$dir/module.php";
            if ( file_exists( $file ) )
            {
                if ( $module === null )
                    $module = new eZModule( $path, $file, $moduleName, false );
                else
                    $module->initialize( $path, $file, $moduleName, false );
                return $module;
            }
            else if ( !file_exists( $dir ) )
            {
                $triedDirList[] = $dir;
            }
            else
            {
                $foundADir = true;
                $triedList[] = $dir;
            }
        }

        $msg = "Could not find module named '$moduleName'";
        if ( $foundADir )
        {
            $msg = "\nThese directories had a directory named '$moduleName' but did not contain the module.php file:\n" .
                   implode( ", ", $triedList ) . "\n" .
                   "This usually means it is missing or has a wrong name.";
            if ( count( $triedDirList ) > 0 )
                $msg .= "\n\nThese directories were tried too but none of them exists:\n" . implode( ', ', $triedDirList );
        }
        else
        {
            if ( count( $triedDirList ) > 0 )
                $msg.= "\nThese directories were tried but none of them exists:\n" . implode( ", ", $triedDirList );
        }
        if ( $showError )
            eZDebug::writeWarning( $msg );

        return null;
    }

    /**
     * Returns the named parameters array
     * @return array
     */
    function getNamedParameters()
    {
        return $this->NamedParameters;
    }

    /**
     * List of defined views for the module, as defined in the $ViewList variable
     * in module.php
     * @var array
     * @private
     */
    public $Functions;
    
    /**
     * Array of module information.
     * Available keys:
     * - string  name: the module name
     * - array   function: the known function (view) list
     * - boolean variable_params
     * - string  ui_component_match
     * @var array
     * @private
     */
    public $Module;
    
    /**
     * The module name
     * @var string
     */
    public $Name;
    
    /**
     * The module's path, without the module name and module.php
     * Examples: kernel, extension/mymoduleextension/modules
     * @var string
     */
    public $Path;
    
    /**
     * The last execution's exit status.
     * Accepts one of the STATUS_ constants.
     * @see STATUS_OK, STATUS_FAILED, STATUS_REDIRECT, STATUS_RERUN
     * @see setExitStatus(), exitStatus()
     * @var int
     */
    public $ExitStatus;
    
    /**
     * The last execution's error code, if an error occured
     * @see errorCode(), setErrorCode()
     * @var int
     */
    public $ErrorCode;
    
    /**
     * The redirection URI that will be used to redirect after execution has ended.
     * @see redirectURI(), setRedirectURI(), redirectTo(), STATUS_REDIRECT
     * @var string
     */
    public $RedirectURI;
    
    /**
     * The redirection HTTP status
     * @see setRedirectStatus(), redirectStatus(), STATUS_REDIRECT
     * @var string
     */
    public $RedirectStatus;
    
    /**
     * The last execution's result title
     * @var string
     * @see title(), setTitle()
     */
    public $Title;
    
    /**
     * The hook list for this module
     * @see addHook(), runHooks()
     * @var array
     */
    public $HookList;
    
    /**
     * Current action per view, as an associative array.
     * Each key is a view name, and the value the current action
     * @var array
     * @see viewAction(), setCurrentAction(), isCurrentAction()
     */
    public $ViewActions;
    
    /**
     * The last execution view result, as an array
     * Common keys: content, title, url...
     * @var array
     */
    public $ViewResult;
    
    /**
     * Ordered view parameters values
     * @var array
     * @private
     */
    public $ViewParameters;
    
    /**
     * Original parameters, before they're mapped to view/unordered/user
     * @var array
     * @private
     */
    public $OriginalParameters;
    
    /**
     * View parameters values
     * @var array
     * @private
     */
    public $OriginalViewParameters;
    
    /**
     * Named parameters, indexed by name
     * @var array
     * @private
     */
    public $NamedParameters;
    
    /**
     * Unordered parameters
     * @var array
     * @private
     */
    public $OriginalUnorderedParameters;
    
    /**
     * User parameters (customized ones, as the content/view "view" parameters)
     * @var array
     * @private
     */
    public $UserParameters;

    /**
     * The current UI context
     * By default 'navigation' but can be changed depending on module or PHP code
     * @var string
     * @private
     */
    public $UIContext;
    
    /**
     * The current UI context
     * By default the current module but can be changed depending on module or PHP code
     * @var string
     * @private
     */
    public $UIComponent;
    
    /**
    * Controls at which level UI component matching is done:
    * either 'module' which uses module name or 'view' which uses view name
    * @var string
    * @private
    */
    public $UIComponentMatch;
}

?>