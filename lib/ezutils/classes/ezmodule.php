<?php
//
// Definition of eZModule class
//
// Created on: <17-Apr-2002 11:11:39 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*!
  \class eZModule ezmodule.php
  \ingroup eZUtils
  \brief Allows execution of modules and functions

*/

require_once( "lib/ezutils/classes/ezdebug.php" );

class eZModule
{
    const STATUS_IDLE = 0;
    const STATUS_OK = 1;
    const STATUS_FAILED = 2;
    const STATUS_REDIRECT = 3;
    const STATUS_RERUN = 4;

    const HOOK_STATUS_OK = 0;
    const HOOK_STATUS_CANCEL_RUN = 1;
    const HOOK_STATUS_FAILED = 2;

    function eZModule( $path, $file, $moduleName )
    {
        $this->initialize( $path, $file, $moduleName );
    }

    /*!
     \private
     Initializes the module object with the path and file and name.
     It will look for a file called \a $file and include the contents
     of that file, it will then assume that some variables were set
     which defines the module and it's view/functions.
    */
    function initialize( $path, $file, $moduleName )
    {
        if ( file_exists( $file ) )
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

    /*!
     \return the URI of the module.
     \sa functionURI
    */
    function uri()
    {
        return "/" . $this->Name;
    }

    /*!
     \return the URI to the view \a $function. If the view is empty or the module is a singleView type
             it will return the result of uri(). If the view does not exist the \c null is returned.
     \sa uri
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

    /*!
     \return the title of the current view run, it's normally set by the view
             and display as the title of view pages.
    */
    function title()
    {
        return $this->Title;
    }

    /*!
     Sets the current view for the module to \a $title.
    */
    function setTitle( $title )
    {
        $this->Title = $title;
    }

    /*!
     Sets the name of the currently running module to \a $name.
    */
    function setCurrentName( $name )
    {
        $this->Name = $name;
        foreach( $this->Functions as $key => $dummy )
        {
            $this->Functions[$key]["uri"] = "/$name/$key";
        }
    }

    /*!
     Sets the name of the currently running view to \a $name.
    */
    function setCurrentView( $name )
    {
        $GLOBALS['eZModuleCurrentView'] = $name;
    }

    /*!
     \return true if the module acts a single view.
    */
    function singleFunction()
    {
        return count( $this->Functions ) == 0;
    }

    /*!
     \return A string describing the current UI context, the default is \c 'navigation'

     Change the context with setUIContextName().
    */
    function uiContextName()
    {
        return $this->UIContext;
    }

    /*!
     \return A string describing the current UI component.

     The default value is the name of the currently running module, can be changed with setUIComponentName().
    */
    function uiComponentName()
    {
        return $this->UIComponent;
    }

    /*!
     Sets the current context string to \a $context.
    */
    function setUIContextName( $context )
    {
        $this->UIContext = $context;
    }

    /*!
     Sets the current component string to \a $component.
    */
    function setUIComponentName( $component )
    {
        $this->UIComponent = $component;
    }

    /*!
     \return the current status from the module.
    */
    function exitStatus()
    {
        return $this->ExitStatus;
    }

    /*!
     Sets the current status for the module to \a $stat, the status can trigger
     a redirect or tell the client that the view failed.
    */
    function setExitStatus( $stat )
    {
        $this->ExitStatus = $stat;
    }

    /*!
     \return the error code if the function failed to run or \c 0 if no error code.
     \sa setErrorCode
    */
    function errorCode()
    {
        return $this->ErrorCode;
    }

    /*!
     Sets the current error code.
     \note You need to set the exit status to eZModule::STATUS_FAILED for the error code to be used.
     \sa setExitStatus, errorCode
    */
    function setErrorCode( $errorCode )
    {
        $this->ErrorCode = $errorCode;
    }

    /*!
     \return the name of the module which will be run on errors.
             The default name is 'error'.
     \sa handleError
    */
    function errorModule()
    {
        if ( !isset( $GLOBALS['eZModuleGlobalErrorModule'] ) )
            $GLOBALS['eZModuleGlobalErrorModule'] = array( 'module' => 'error',
                                        'view' => 'view' );
        return $GLOBALS['eZModuleGlobalErrorModule'];
    }

    /*!
     Sets the name of the module which will be run on errors.
     \sa handleError
    */
    function setErrorModule( $moduleName, $viewName )
    {
        $GLOBALS['eZModuleGlobalErrorModule'] = array( 'module' => $moduleName,
                                                       'view' => $viewName );
    }

    /*!
     Tries to run the error module with the error code \a $errorCode.
     Sets the state of the module object to \c failed and sets the error code.
    */
    function handleError( $errorCode, $errorType = false, $parameters = array(), $userParameters = false )
    {
        if ( !$errorType )
        {
            eZDebug::writeWarning( "No error type specified for error code $errorCode, assuming kernel.\nA specific error type should be supplied, please check your code.",
                                   'eZModule::handleError' );
            $errorType = 'kernel';
        }
        $errorModule = eZModule::errorModule();
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

    /*!
     Redirects the page to the module \a $moduleName and view \a $viewName with parameters \a $parameters
     and unorderedParameters \a $unorderedParameters. If you already have the module object use redirectModule
     instead or if you need to redirect to a view in the current module use redirectToView.
     \return false if the view could not redirected to.
     \sa redirectionURI
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

    /*!
     Same as redirect() only redirects in the current module.
    */
    function redirectToView( $viewName = '', $parameters = array(),
                             $unorderedParameters = null, $userParameters = false,
                             $anchor = false )
    {
        return $this->redirectModule( $this, $viewName, $parameters,
                                      $unorderedParameters, $userParameters, $anchor );
    }

    /*!
     Same as redirect() but takes a module object instead of the name.
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

    /*!
     \return the URI for the module \a $moduleName and view \a $viewName using parameters \a $parameters
             and unordered parameters \a $unorderedParameters.
     \sa redirect
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

    /*!
     \return The URI of the currently run view in the current module with the current parameters.
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

    /*!
     Redirects to the current module and view, it will use currentRedirectionURI() to
     figure out the URL.
     \note By changing using setCurrentName() and setCurrentView() first it is possible to
           redirect to another module or view with the same parameters.
    */
    function redirectCurrent()
    {
        $this->redirectTo( $this->currentRedirectionURI() );
    }

    /*!
     Sames as redirectionURI but takes a module object instead of the name.
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

    /*!
     \return the parameter definition for the view \a $viewName. If \a $viewName
             is empty the current view is used.
     \sa unorderedParameters, viewData, currentView, currentModule
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
        return $retValue;
    }

    /*!
     \return the unordered parameter definition for the view \a $viewName. If \a $viewName
             is empty the current view is used.
     \sa parameters, viewData, currentView, currentModule
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

    /*!
     \return the view data for the view \a $viewName. If \a $viewName
             is empty the current view is used.
     \sa parameters, unorderedParameters, currentView, currentModule
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

    /*!
     Makes sure that the module is redirected to the URI \a $uri when the function exits.
     \sa setRedirectURI, setExitStatus
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

    /*!
     \return the URI which will be redirected to when the function exits.
    */
    function redirectURI()
    {
        return $this->RedirectURI;
    }

    /*!
     Sets the URI which will be redirected to when the function exits.
    */
    function setRedirectURI( $uri )
    {
        $this->RedirectURI = $uri;
    }

    /*!
     \return the status which will be set when redirecting.
    */
    function redirectStatus()
    {
        return $this->RedirectStatus;
    }

    /*!
     Sets the status which will be set when redirecting.
     \note The status must be a valid HTTP status with number and text.
    */
    function setRedirectStatus( $status )
    {
        $this->RedirectStatus = $status;
    }

    /*!
     \return an array with the available attributes.
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

    /*!
     \return true if the attribute \a $attr is available.
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \return the attribute value for attribute \a $attr if it is available, otherwise \c null.
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

    /*!
     Sets the current action in view \a $view to \a $actionName.
     \sa currentAction, isCurrentAction
    */
    function setCurrentAction( $actionName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( $view == '' or $actionName == '' )
            return false;
        $this->ViewActions[$view] = $actionName;
    }

    /*!
     \return the current action for the view \a $view.

     If the current action is not yet determined it will use the definitions in
     \c module.php for finding out the current action. It first looks trough
     the \c single_post_actions array in the selected view mode, the key to
     each element is the name of the post-variable to match, if it matches the
     element value is set as the action.
     \code
    'single_post_actions' => array( 'PreviewButton' => 'Preview',
                                    'PublishButton' => 'Publish' )
     \endcode
     If none of these matches it will use the elements from the \c post_actions
     array to find a match. It uses the element value for each element to match
     agains a post-variable, if it is found the contents of the post-variable
     is set as the action.
     \code
    'post_actions' => array( 'BrowseActionName' )
     \endcode
     \sa setCurrentAction
    */
    function currentAction( $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActions[$view] ) )
            return $this->ViewActions[$view];
        //include_once( "lib/ezutils/classes/ezhttptool.php" );
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

    function setActionParameter( $parameterName, $parameterValue, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        $this->ViewActionParameters[$view][$parameterName] = $parameterValue;
    }

    function actionParameter( $parameterName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActionParameters[$view][$parameterName] ) )
            return $this->ViewActionParameters[$view][$parameterName];
        $currentAction = $this->currentAction( $view );
        //include_once( "lib/ezutils/classes/ezhttptool.php" );
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

    function hasActionParameter( $parameterName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( isset( $this->ViewActionParameters[$view][$parameterName] ) )
            return true;
        $currentAction = $this->currentAction( $view );
        //include_once( "lib/ezutils/classes/ezhttptool.php" );
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

    /*!
     \return true if the current action matches the action name \a $actionName in view \a $view.
             Always returns false if either \a $view or \a $actionName is empty.
     \sa currentAction, setCurrentAction
    */
    function isCurrentAction( $actionName, $view = '' )
    {
        if ( $view == '' )
            $view = eZModule::currentView();
        if ( $view == '' or $actionName == '' )
            return false;
        return $this->currentAction( $view ) == $actionName;
    }

    /*!
     Adds an entry to the hook named \a $hookName. The entry is placed
     before all other existing entries unless \a $append is set to \c true
     in which case the entry is placed at the end.
     \param $function Either the name of the function to be run or an
                      array where the first entry is the object and the second
                      is the method name.
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

    /*!
     Runs all hooks found in the hook list named \a $hookName.
     The parameter array \a $parameters will be passed to each hook function.
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

    function setViewResult( $result, $view = '' )
    {
        if ( $view == '' )
            $view = $this->currentView();
        $this->ViewResult[$view] = $result;
    }

    function hasViewResult( $view = '' )
    {
        if ( $view == '' )
            $view = $this->currentView();
        return isset( $this->ViewResult[$view] );
    }

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

    /*!
     Tries to run the function \a $functionName in the current module.
     \param parameters An indexed list of parameters, these will be mapped
                       onto real parameter names using the defined parameter
                       names in the module/function definition.
                       If this variable is shorter than the required parameters
                       they will be set to \c null.
     \param overrideParameters An associative array of parameters which
                               will override any parameters found using the
                               defined parameters.
     \return null if function could not be run or no return value was found.
    */
    function run( $functionName, $parameters, $overrideParameters = false, $userParameters = false )
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
        //include_once( "lib/ezutils/classes/ezprocess.php" );
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

    /*!
     \static
     \return the current view which is run or \c false if no view is active.
     \sa currentModule
    */
    function currentView()
    {
        $currentView = $GLOBALS['eZModuleCurrentView'];
        if ( $currentView !== false )
            return $currentView['view'];
        return false;
    }

    /*!
     \static
     \return the current module which is run or \c false if no module is active.
     \sa currentModule
    */
    function currentModule()
    {
        $currentView = $GLOBALS['eZModuleCurrentView'];
        if ( $currentView !== false )
            return $currentView['module'];
        return false;
    }

    /*!
     \static
     \return the global path list which is used for finding modules. Returns \c null if no
             list is available.
     \sa setGlobalPathList, addGlobalPathList
    */
    static function globalPathList()
    {
        if ( !isset( $GLOBALS['eZModuleGlobalPathList'] ) )
            return null;
        return $GLOBALS['eZModuleGlobalPathList'];
    }

    /*!
     \static
     \return a path list of currently active modules
    */
    static function activeModuleRepositories( $useExtensions = true )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        //include_once( 'lib/ezutils/classes/ezextension.php' );
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
                if ( file_exists( $modulePath ) )
                {
                    $globalExtensionRepositories[] = $modulePath;
                }
                else if ( !file_exists( $extPath ) )
                {
                    eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but the extension itself does not exist.\n" .
                                           "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions.\n" .
                                           "You should probably remove this extension from the list." );
                }
                else if ( !in_array( $extensionRepository, $activeExtensions ) )
                {
                    eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but has not yet been activated.\n" .
                                           "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions\n" .
                                           "or make sure it is activated in the setting ExtensionSettings/ActiveExtensions in site.ini." );
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

    /*!
     \static
     Sets the global path list which is used for finding modules.
     \param $pathList Is either an array with path strings or a single path string
     \sa addGlobalPathList
    */
    static function setGlobalPathList( $pathList )
    {
        if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $GLOBALS['eZModuleGlobalPathList'] = $pathList;
    }

    /*!
     \static
     Adds the pathlist entries \a $pathList to the global path list which is used for finding modules.
     \param $pathList Is either an array with path strings or a single path string
     \sa setGlobalPathList
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

    /*!
     \static
     Tries to locate the module named \a $moduleName and returns an eZModule object
     for it. Returns \c null if no module can be found.

     It uses the globalPathList() to search for modules, use \a $pathList to add
     additional path.
     \param $moduleName The name of the module to find
     \param $pathList Is either an array with path strings or a single path string
     \param $showError If true an error will be shown if the module it not found.
    */
    static function exists( $moduleName, $pathList = null, $showError = false )
    {
        $module = null;
        return eZModule::findModule( $moduleName, $module, $pathList, $showError );
    }

    /*!
     \static
     Tries to locate the module named \a $moduleName, sets the \a $module parameter with the eZModule object
     and returns the eZModule object. If \a $module is already a module object its contents are overwritten.
     Returns \c null if no module can be found.

     It uses the globalPathList() to search for modules, use \a $pathList to add
     additional path.
     \param $moduleName The name of the module to find
     \param $module
     \param $pathList Is either an array with path strings or a single path string
     \param $showError If true an error will be shown if the module it not found.
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
                    $module = new eZModule( $path, $file, $moduleName );
                else
                    $module->initialize( $path, $file, $moduleName );
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

    function getNamedParameters()
    {
        return $this->NamedParameters;
    }

    /// \privatesection
    public $Functions;
    public $Module;
    public $Name;
    public $Path;
    public $ExitStatus;
    public $ErrorCode;
    public $RedirectURI;
    public $RedirectStatus;
    public $Title;
    public $HookList;
    public $ViewActions;
    public $ViewResult;
    public $ViewParameters;
    public $OriginalParameters;
    public $OriginalViewParameters;
    public $NamedParameters;
    public $OriginalUnorderedParameters;
    public $UserParameters;

    /// The current UI context, by default 'navigation' but can be changed depending on module or PHP code
    public $UIContext;
    /// The current UI context, by default the current module but can be changed depending on module or PHP code
    public $UIComponent;
    /// Controls at which level UI component matching is done, either 'module' which uses module name or 'view' which uses view name
    public $UIComponentMatch;
}

?>
