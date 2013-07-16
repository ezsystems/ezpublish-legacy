<?php
/**
 * File containing the ezpKernelWeb class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

/**
 * Provides a kernel handler in web context
 *
 * Allows kernel to be executed as Controller via run()
 */
class ezpKernelWeb implements ezpWebBasedKernelHandler
{
    /**
     * @var ezpMobileDeviceDetect
     */
    private $mobileDeviceDetect;

    /**
     * @var array
     */
    private $policyCheckViewMap;

    /**
     * @var eZModule
     */
    private $module;

    /**
     * @var array
     */
    private $warningList = array();

    /**
     * @var array
     */
    private $siteBasics;

    /**
     * @var string
     */
    private $actualRequestedURI;

    /**
     * @var string
     */
    private $oldURI;

    /**
     * @var string
     */
    private $completeRequestedURI;

    /**
     * Current siteaccess data
     *
     * @var array
     */
    private $access;

    /**
     * @var eZURI
     */
    private $uri;

    /**
     * @var array
     */
    private $check;

    /**
     * @var array
     */
    private $site;

    /**
     * @see eZLocale::httpLocaleCode()
     * @var string
     */
    private $languageCode;

    /**
     * @see eZTextCodec::httpCharset()
     * @var string
     */
    private $httpCharset;

    /**
     * Indicates is request has been properly initialized
     *
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * Hash of settings for the web kernel handler.
     *
     * Keys can be:
     *  - siteaccess (injected siteaccess, an associative array with 'name' (string), 'type' (int) and 'uri_part' (array))
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Constructs an ezpKernel instance
     */
    public function __construct( array $settings = array() )
    {
        if ( isset( $settings['injected-settings'] ) )
        {
            $injectedSettings = array();
            foreach ( $settings["injected-settings"] as $keySetting => $injectedSetting )
            {
                list( $file, $section, $setting ) = explode( "/", $keySetting );
                $injectedSettings[$file][$section][$setting] = $injectedSetting;
            }
            // those settings override anything else in local .ini files and
            // their overrides
            eZINI::injectSettings( $injectedSettings );
        }
        $this->settings = $settings + array(
            'siteaccess'            => null,
            'use-exceptions'        => false,
            'session'               => null,
            'service-container'     => null,
        );
        unset( $settings, $injectedSettings, $file, $section, $setting, $keySetting, $injectedSetting );

        require_once __DIR__ . '/global_functions.php';
        $this->setUseExceptions( $this->settings['use-exceptions'] );

        $GLOBALS['eZSiteBasics'] = array(
            'external-css' => true,
            'show-page-layout' => true,
            'module-run-required' => true,
            'policy-check-required' => true,
            'policy-check-omit-list' => array(),// List of module names which will skip policy checking
            'url-translator-allowed' => true,
            'validity-check-required' => false,
            'user-object-required' => true,
            'session-required' => true,
            'db-required' => false,
            'no-cache-adviced' => false,
            'site-design-override' => false,
            'module-repositories' => array(),// List of directories to search for modules
        );
        $this->siteBasics =& $GLOBALS['eZSiteBasics'];

        // Reads settings from i18n.ini and passes them to eZTextCodec.
        list(
            $i18nSettings['internal-charset'],
            $i18nSettings['http-charset'],
            $i18nSettings['mbstring-extension']
        ) = eZINI::instance( 'i18n.ini' )->variableMulti(
            'CharacterSettings',
                array( 'Charset', 'HTTPCharset', 'MBStringExtension' ),
                array( false, false, 'enabled' )
        );

        eZTextCodec::updateSettings( $i18nSettings );// @todo Change so code only supports utf-8 in 5.0?

        // Initialize debug settings.
        eZUpdateDebugSettings();

        // Set the different permissions/settings.
        $ini = eZINI::instance();

        // Set correct site timezone
        $timezone = $ini->variable( "TimeZoneSettings", "TimeZone");
        if ( $timezone )
        {
            date_default_timezone_set( $timezone );
        }

        list( $iniFilePermission, $iniDirPermission ) =
            $ini->variableMulti( 'FileSettings', array( 'StorageFilePermissions', 'StorageDirPermissions' ) );

        // OPTIMIZATION:
        // Sets permission array as global variable, this avoids the eZCodePage include
        $GLOBALS['EZCODEPAGEPERMISSIONS'] = array(
            'file_permission' => octdec( $iniFilePermission ),
            'dir_permission'  => octdec( $iniDirPermission ),
            'var_directory'   => eZSys::cacheDirectory()
        );
        unset( $i18nSettings, $timezone, $iniFilePermission, $iniDirPermission );

        eZExecution::addCleanupHandler(
            function()
            {
                if ( class_exists( 'eZDB', false ) && eZDB::hasInstance() )
                {
                    eZDB::instance()->setIsSQLOutputEnabled( false );
                }
            }
        );

        eZExecution::addFatalErrorHandler(
            function()
            {
                header("HTTP/1.1 500 Internal Server Error");
                echo "<b>Fatal error</b>: The web server did not finish its request<br/>";
                if ( ini_get('display_errors') == 1 )
                {
                    if ( eZDebug::isDebugEnabled() )
                        echo "<p>The execution of eZ Publish was abruptly ended, the debug output is present below.</p>";
                    else
                        echo "<p>Debug information can be found in the log files normally placed in var/log/* or by enabling 'DebugOutput' in site.ini</p>";
                }
                else
                {
                    echo "<p>Contact website owner with current url and info on what you did, and owner will be able to debug the issue further (by enabling 'display_errors' in php.ini).</p>";
                }
                eZDisplayResult( null );
            }
        );
        eZExecution::setCleanExit();

        // Enable this line to get eZINI debug output
        // eZINI::setIsDebugEnabled( true );
        // Enable this line to turn off ini caching
        // eZINI::setIsCacheEnabled( false);

        if ( $ini->variable( 'RegionalSettings', 'Debug' ) === 'enabled' )
            eZLocale::setIsDebugEnabled( true );

        eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

        $GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

        // Initialize basic settings, such as vhless dirs and separators
        if ( $this->hasServiceContainer() && $this->getServiceContainer()->has( 'request' ) )
        {
            eZSys::init(
                basename( $this->getServiceContainer()->get( 'request' )->server->get( 'SCRIPT_FILENAME' ) ),
                $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true'
            );
        }
        else
        {
            eZSys::init(
                'index.php',
                $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) === 'true'
            );
        }

        // Check for extension
        eZExtension::activateExtensions( 'default' );
        // Extension check end

        // Use injected siteaccess if available or match it internally.
        $this->access = isset( $this->settings['siteaccess'] ) ?
            $this->settings['siteaccess'] :
            eZSiteAccess::match(
                eZURI::instance( eZSys::requestURI() ),
                eZSys::hostname(),
                eZSys::serverPort(),
                eZSys::indexFile()
            )
        ;

        eZSiteAccess::change( $this->access );
        eZDebugSetting::writeDebug( 'kernel-siteaccess', $this->access, 'current siteaccess' );

        // Check for siteaccess extension
        eZExtension::activateExtensions( 'access' );
        // Siteaccess extension check end

        // Now that all extensions are activated and siteaccess has been changed, reset
        // all eZINI instances as they may not take into account siteaccess specific settings.
        eZINI::resetAllInstances( false );

        ezpEvent::getInstance()->registerEventListeners();

        $this->mobileDeviceDetect = new ezpMobileDeviceDetect( ezpMobileDeviceDetectFilter::getFilter() );
        // eZSession::setSessionArray( $mainRequest->session );
    }

    /**
     * Execution point for controller actions
     */
    public function run()
    {
        if ( $this->mobileDeviceDetect->isEnabled() )
        {
            $this->mobileDeviceDetect->process();

            if ( $this->mobileDeviceDetect->isMobileDevice() )
                $this->mobileDeviceDetect->redirect();
        }

        ob_start();
        $this->requestInit();

        // send header information
        foreach (
            eZHTTPHeader::headerOverrideArray( $this->uri ) +
            array(
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'X-Powered-By' => eZPublishSDK::EDITION,
                'Content-Type' => 'text/html; charset=' . $this->httpCharset,
                'Served-by' => isset( $_SERVER["SERVER_NAME"] ) ? $_SERVER['SERVER_NAME'] : null,
                'Content-language' => $this->languageCode
              ) as $key => $value
        )
        {
            header( $key . ': ' . $value );
        }

        try
        {
            $moduleResult = $this->dispatchLoop();
        }
        catch ( Exception $e )
        {
            $this->shutdown();
            throw $e;
        }

        $ini = eZINI::instance();

        /**
         * Ouput an is_logged_in cookie when users are logged in for use by http cache solutions.
         *
         * @deprecated As of 4.5, since 4.4 added lazy session support (init on use)
         */
        if ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) !== 'true' )
        {
            $wwwDir = eZSys::wwwDir();
            // On host based site accesses this can be empty, causing the cookie to be set for the current dir,
            // but we want it to be set for the whole eZ publish site
            $cookiePath = $wwwDir != '' ? $wwwDir : '/';

            if ( eZUser::isCurrentUserRegistered() )
            {
                // Only set the cookie if it doesnt exist. This way we are not constantly sending the set request in the headers.
                if ( !isset( $_COOKIE['is_logged_in'] ) || $_COOKIE['is_logged_in'] !== 'true' )
                {
                    setcookie( 'is_logged_in', 'true', 0, $cookiePath );
                }
            }
            else if ( isset( $_COOKIE['is_logged_in'] ) )
            {
                setcookie( 'is_logged_in', false, 0, $cookiePath );
            }
        }

        if ( $this->module->exitStatus() == eZModule::STATUS_REDIRECT )
        {
            $this->redirect();
        }

        $uiContextName = $this->module->uiContextName();

        // Store the last URI for access history for login redirection
        // Only if user has session and only if there was no error or no redirects happen
        if ( eZSession::hasStarted() && $this->module->exitStatus() == eZModule::STATUS_OK )
        {
            $currentURI = $this->completeRequestedURI;
            if ( strlen( $currentURI ) > 0 && $currentURI[0] !== '/' )
                $currentURI = '/' . $currentURI;

            $lastAccessedURI = "";
            $lastAccessedViewURI = "";

            $http = eZHTTPTool::instance();

            // Fetched stored session variables
            if ( $http->hasSessionVariable( "LastAccessesURI" ) )
            {
                $lastAccessedViewURI = $http->sessionVariable( "LastAccessesURI" );
            }
            if ( $http->hasSessionVariable( "LastAccessedModifyingURI" ) )
            {
                $lastAccessedURI = $http->sessionVariable( "LastAccessedModifyingURI" );
            }

            // Update last accessed view page
            if ( $currentURI != $lastAccessedViewURI &&
                 !in_array( $uiContextName, array( 'edit', 'administration', 'browse', 'authentication' ) ) )
            {
                $http->setSessionVariable( "LastAccessesURI", $currentURI );
            }

            // Update last accessed non-view page
            if ( $currentURI != $lastAccessedURI && $uiContextName != 'ajax' )
            {
                $http->setSessionVariable( "LastAccessedModifyingURI", $currentURI );
            }
        }

        eZDebug::addTimingPoint( "Module end '" . $this->module->attribute( 'name' ) . "'" );
        if ( !is_array( $moduleResult ) )
        {
            eZDebug::writeError( 'Module did not return proper result: ' . $this->module->attribute( 'name' ), 'index.php' );
            $moduleResult = array();
            $moduleResult['content'] = false;
        }

        if ( !isset( $moduleResult['ui_context'] ) )
        {
            $moduleResult['ui_context'] = $uiContextName;
        }
        $moduleResult['ui_component'] = $this->module->uiComponentName();
        $moduleResult['is_mobile_device'] = $this->mobileDeviceDetect->isMobileDevice();
        $moduleResult['mobile_device_alias'] = $this->mobileDeviceDetect->getUserAgentAlias();

        $templateResult = null;

        eZDebug::setUseExternalCSS( $this->siteBasics['external-css'] );
        if ( $this->siteBasics['show-page-layout'] )
        {
            $tpl = eZTemplate::factory();
            if ( $tpl->hasVariable( 'node' ) )
                $tpl->unsetVariable( 'node' );

            if ( !isset( $moduleResult['path'] ) )
                $moduleResult['path'] = false;
            $moduleResult['uri'] = eZSys::requestURI();

            $tpl->setVariable( "module_result", $moduleResult );

            $meta = $ini->variable( 'SiteSettings', 'MetaDataArray' );

            if ( !isset( $meta['description'] ) )
            {
                $metaDescription = "";
                if ( isset( $moduleResult['path'] ) && is_array( $moduleResult['path'] ) )
                {
                    foreach ( $moduleResult['path'] as $pathPart )
                    {
                        if ( isset( $pathPart['text'] ) )
                            $metaDescription .= $pathPart['text'] . " ";
                    }
                }
                $meta['description'] = $metaDescription;
            }

            $this->site['uri'] = $this->oldURI;
            $this->site['redirect'] = false;
            $this->site['meta'] = $meta;
            $this->site['version'] = eZPublishSDK::version();
            $this->site['page_title'] = $this->module->title();

            $tpl->setVariable( "site", $this->site );

            if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) === 'enabled' )
            {
                // Make sure any errors or warnings are reported
                if ( isset( $GLOBALS['eZDebugError'] ) && $GLOBALS['eZDebugError'] )
                {
                    eZAppendWarningItem(
                        array(
                            'error' => array(
                                'type' => 'error',
                                'number' => 1 ,
                                'count' => $GLOBALS['eZDebugErrorCount']
                            ),
                            'identifier' => 'ezdebug-first-error',
                            'text' => ezpI18n::tr( 'index.php', 'Some errors occurred, see debug for more information.' )
                        )
                    );
                }

                if ( isset( $GLOBALS['eZDebugWarning'] ) && $GLOBALS['eZDebugWarning'] )
                {
                    eZAppendWarningItem(
                        array(
                            'error' => array(
                                'type' => 'warning',
                                'number' => 1,
                                'count' => $GLOBALS['eZDebugWarningCount']
                            ),
                            'identifier' => 'ezdebug-first-warning',
                            'text' => ezpI18n::tr( 'index.php', 'Some general warnings occured, see debug for more information.' )
                        )
                    );
                }
            }

            if ( $this->siteBasics['user-object-required'] )
            {
                $currentUser = eZUser::currentUser();

                $tpl->setVariable( "current_user", $currentUser );
                $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
            }
            else
            {
                $tpl->setVariable( "current_user", false );
                $tpl->setVariable( "anonymous_user_id", false );
            }

            $tpl->setVariable( "access_type", $this->access );
            $tpl->setVariable( 'warning_list', !empty( $this->warningList) ? $this->warningList : false );

            $resource = "design:";
            if ( is_string( $this->siteBasics['show-page-layout'] ) )
            {
                if ( strpos( $this->siteBasics['show-page-layout'], ":" ) !== false )
                {
                    $resource = "";
                }
            }
            else
            {
                $this->siteBasics['show-page-layout'] = "pagelayout.tpl";
            }

            // Set the navigation part
            // Check for navigation part settings
            $navigationPartString = 'ezcontentnavigationpart';
            if ( isset( $moduleResult['navigation_part'] ) )
            {
                $navigationPartString = $moduleResult['navigation_part'];

                // Fetch the navigation part
            }
            $navigationPart = eZNavigationPart::fetchPartByIdentifier( $navigationPartString );

            $tpl->setVariable( 'navigation_part', $navigationPart );
            $tpl->setVariable( 'uri_string', $this->uri->uriString() );
            if ( isset( $moduleResult['requested_uri_string'] ) )
            {
                $tpl->setVariable( 'requested_uri_string', $moduleResult['requested_uri_string'] );
            }
            else
            {
                $tpl->setVariable( 'requested_uri_string', $this->actualRequestedURI );
            }

            // Set UI context and component
            $tpl->setVariable( 'ui_context', $moduleResult['ui_context'] );
            $tpl->setVariable( 'ui_component', $moduleResult['ui_component'] );

            $templateResult = $tpl->fetch( $resource . $this->siteBasics['show-page-layout'] );
        }
        else
        {
            $templateResult = $moduleResult['content'];
        }

        eZDebug::addTimingPoint( "Script end" );

        $content = trim( ob_get_clean() );

        ob_start();
        eZDB::checkTransactionCounter();
        eZDisplayResult( $templateResult );
        $content .= ob_get_clean();

        $this->shutdown();

        return new ezpKernelResult( $content, array( 'module_result' => $moduleResult ) );
    }

    /**
     * Runs the dispatch loop
     */
    protected function dispatchLoop()
    {
        $ini = eZINI::instance();

        // Start the module loop
        while ( $this->siteBasics['module-run-required'] )
        {
            $objectHasMovedError = false;
            $objectHasMovedURI = false;
            $this->actualRequestedURI = $this->uri->uriString();

            // Extract user specified parameters
            $userParameters = $this->uri->userParameters();

            // Generate a URI which also includes the user parameters
            $this->completeRequestedURI = $this->uri->originalURIString();

            // Check for URL translation
            if ( $this->siteBasics['url-translator-allowed'] && eZURLAliasML::urlTranslationEnabledByUri( $this->uri ) )
            {
                $translateResult = eZURLAliasML::translate( $this->uri );

                if ( !is_string( $translateResult ) && $ini->variable( 'URLTranslator', 'WildcardTranslation' ) === 'enabled' )
                {
                    $translateResult = eZURLWildcard::translate( $this->uri );
                }

                // Check if the URL has moved
                if ( is_string( $translateResult ) )
                {
                    $objectHasMovedURI = $translateResult;
                    foreach ( $userParameters as $name => $value )
                    {
                        $objectHasMovedURI .= '/(' . $name . ')/' . $value;
                    }

                    $objectHasMovedError = true;
                }
            }

            if ( $this->uri->isEmpty() )
            {
                $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
                $moduleCheck = eZModule::accessAllowed( $tmp_uri );
            }
            else
            {
                $moduleCheck = eZModule::accessAllowed( $this->uri );
            }

            if ( !$moduleCheck['result'] )
            {
                if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
                {
                    $defaultPage = $ini->variable( "SiteSettings", "DefaultPage" );
                    $this->uri->setURIString( $defaultPage );
                    $moduleCheck['result'] = true;
                }
            }

            $displayMissingModule = false;
            $this->oldURI = $this->uri;

            if ( $this->uri->isEmpty() )
            {
                if ( !fetchModule( $tmp_uri, $this->check, $this->module, $moduleName, $functionName, $params ) )
                    $displayMissingModule = true;
            }
            else if ( !fetchModule( $this->uri, $this->check, $this->module, $moduleName, $functionName, $params ) )
            {
                if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
                {
                    $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "DefaultPage" ) );
                    if ( !fetchModule( $tmp_uri, $this->check, $this->module, $moduleName, $functionName, $params ) )
                        $displayMissingModule = true;
                }
                else
                    $displayMissingModule = true;
            }

            if ( !$displayMissingModule && $moduleCheck['result'] && $this->module instanceof eZModule )
            {
                // Run the module/function
                eZDebug::addTimingPoint( "Module start '" . $this->module->attribute( 'name' ) . "'" );

                $moduleAccessAllowed = true;
                $omitPolicyCheck = true;
                $runModuleView = true;

                $availableViewsInModule = $this->module->attribute( 'views' );
                if (
                    !isset( $availableViewsInModule[$functionName] )
                    && !$objectHasMovedError
                    && !isset( $this->module->Module['function']['script'] )
                )
                {
                    $moduleResult = $this->module->handleError( eZError::KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel', array( "check" => $moduleCheck ) );
                    $runModuleView = false;
                    $this->siteBasics['policy-check-required'] = false;
                    $omitPolicyCheck = true;
                }

                if ( $this->siteBasics['policy-check-required'] )
                {
                    $omitPolicyCheck = false;
                    $moduleName = $this->module->attribute( 'name' );
                    if ( in_array( $moduleName, $this->siteBasics['policy-check-omit-list'] ) )
                        $omitPolicyCheck = true;
                    else
                    {
                        $policyCheckViewMap = $this->getPolicyCheckViewMap( $this->siteBasics['policy-check-omit-list'] );
                        if ( isset( $policyCheckViewMap[$moduleName][$functionName] ) )
                            $omitPolicyCheck = true;
                    }
                }
                if ( !$omitPolicyCheck )
                {
                    $currentUser = eZUser::currentUser();
                    $siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );

                    $hasAccessToSite = false;
                    if ( $siteAccessResult[ 'accessWord' ] === 'limited' )
                    {
                        $policyChecked = false;
                        foreach ( array_keys( $siteAccessResult['policies'] ) as $key )
                        {
                            $policy = $siteAccessResult['policies'][$key];
                            if ( isset( $policy['SiteAccess'] ) )
                            {
                                $policyChecked = true;
                                $crc32AccessName = eZSys::ezcrc32( $this->access[ 'name' ] );
                                eZDebugSetting::writeDebug( 'kernel-siteaccess', $policy['SiteAccess'], $crc32AccessName );
                                if ( in_array( $crc32AccessName, $policy['SiteAccess'] ) )
                                {
                                    $hasAccessToSite = true;
                                    break;
                                }
                            }
                            if ( $hasAccessToSite )
                                break;
                        }
                        if ( !$policyChecked )
                            $hasAccessToSite = true;
                    }
                    else if ( $siteAccessResult[ 'accessWord' ] === 'yes' )
                    {
                        eZDebugSetting::writeDebug( 'kernel-siteaccess', "access is yes" );
                        $hasAccessToSite = true;
                    }
                    else if ( $siteAccessResult['accessWord'] === 'no' )
                    {
                        $accessList = $siteAccessResult['accessList'];
                    }

                    if ( $hasAccessToSite )
                    {
                        $accessParams = array();
                        $moduleAccessAllowed = $currentUser->hasAccessToView( $this->module, $functionName, $accessParams );
                        if ( isset( $accessParams['accessList'] ) )
                        {
                            $accessList = $accessParams['accessList'];
                        }
                    }
                    else
                    {
                        eZDebugSetting::writeDebug( 'kernel-siteaccess', $this->access, 'not able to get access to siteaccess' );
                        $moduleAccessAllowed = false;
                        if ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" )
                        {
                            $this->module = eZModule::exists( 'user' );
                            if ( $this->module instanceof eZModule )
                            {
                                $moduleResult = $this->module->run(
                                    'login',
                                    array(),
                                    array(
                                        'SiteAccessAllowed' => false,
                                        'SiteAccessName' => $this->access['name']
                                    )
                                );
                                $runModuleView = false;
                            }
                        }
                    }
                }

                $GLOBALS['eZRequestedModule'] = $this->module;

                if ( $runModuleView )
                {
                    if ( $objectHasMovedError == true )
                    {
                        $moduleResult = $this->module->handleError( eZError::KERNEL_MOVED, 'kernel', array( 'new_location' => $objectHasMovedURI ) );
                    }
                    else if ( !$moduleAccessAllowed )
                    {
                        if ( isset( $availableViewsInModule[$functionName][ 'default_navigation_part' ] ) )
                        {
                            $defaultNavigationPart = $availableViewsInModule[$functionName][ 'default_navigation_part' ];
                        }

                        if ( isset( $accessList ) )
                            $moduleResult = $this->module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $accessList ) );
                        else
                            $moduleResult = $this->module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

                        if ( isset( $defaultNavigationPart ) )
                        {
                            $moduleResult['navigation_part'] = $defaultNavigationPart;
                            unset( $defaultNavigationPart );
                        }
                    }
                    else
                    {
                        if ( !isset( $userParameters ) )
                        {
                            $userParameters = false;
                        }

                        // Check if we should switch access mode (http/https) for this module view.
                        eZSSLZone::checkModuleView( $this->module->attribute( 'name' ), $functionName );

                        $moduleResult = $this->module->run( $functionName, $params, false, $userParameters );

                        if ( $this->module->exitStatus() == eZModule::STATUS_FAILED && $moduleResult == null )
                            $moduleResult = $this->module->handleError(
                                eZError::KERNEL_MODULE_VIEW_NOT_FOUND,
                                'kernel',
                                array(
                                    'module' => $moduleName,
                                    'view' => $functionName
                                )
                            );
                    }
                }
            }
            else if ( $moduleCheck['result'] )
            {
                eZDebug::writeError( "Undefined module: $moduleName", "index" );
                $this->module = new eZModule( "", "", $moduleName );
                $GLOBALS['eZRequestedModule'] = $this->module;
                $moduleResult = $this->module->handleError( eZError::KERNEL_MODULE_NOT_FOUND, 'kernel', array( 'module' => $moduleName ) );
            }
            else
            {
                if ( $moduleCheck['view_checked'] )
                    eZDebug::writeError( "View '" . $moduleCheck['view'] . "' in module '" . $moduleCheck['module'] . "' is disabled", "index" );
                else
                    eZDebug::writeError( "Module '" . $moduleCheck['module'] . "' is disabled", "index" );
                $GLOBALS['eZRequestedModule'] = $this->module = new eZModule( "", "", $moduleCheck['module'] );
                $moduleResult = $this->module->handleError( eZError::KERNEL_MODULE_DISABLED, 'kernel', array( 'check' => $moduleCheck ) );
            }
            $this->siteBasics['module-run-required'] = false;
            if ( $this->module->exitStatus() == eZModule::STATUS_RERUN )
            {
                if ( isset( $moduleResult['rerun_uri'] ) )
                {
                    $this->uri = eZURI::instance( $moduleResult['rerun_uri'] );
                    $this->siteBasics['module-run-required'] = true;
                }
                else
                    eZDebug::writeError( 'No rerun URI specified, cannot continue', 'index.php' );
            }

            if ( is_array( $moduleResult ) )
            {
                if ( isset( $moduleResult["pagelayout"] ) )
                {
                    $this->siteBasics['show-page-layout'] = $moduleResult["pagelayout"];
                    $GLOBALS['eZCustomPageLayout'] = $moduleResult["pagelayout"];
                }
                if ( isset( $moduleResult["external_css"] ) )
                    $this->siteBasics['external-css'] = $moduleResult["external_css"];
            }
        }

        return $moduleResult;
    }

    /**
     * Returns the map for policy check view
     *
     * @param array $policyCheckOmitList
     *
     * @return array
     */
    protected function getPolicyCheckViewMap( array $policyCheckOmitList )
    {
        if ( $this->policyCheckViewMap !== null )
            return $this->policyCheckViewMap;

        $this->policyCheckViewMap = array();
        foreach ( $policyCheckOmitList as $omitItem )
        {
            $items = explode( '/', $omitItem );
            if ( count( $items ) > 1 )
            {
                $module = $items[0];
                if ( !isset( $this->policyCheckViewMap[$module] ) )
                    $this->policyCheckViewMap[$module] = array();
                $this->policyCheckViewMap[$module][$items[1]] = true;
            }
        }

        return $this->policyCheckViewMap;
    }

    /**
     * Performs a redirection
     */
    protected function redirect()
    {
        $GLOBALS['eZRedirection'] = true;
        $ini = eZINI::instance();
        $automaticRedirect = true;

        if ( $GLOBALS['eZDebugAllowed'] && ( $redirUri = $ini->variable( 'DebugSettings', 'DebugRedirection' ) ) !== 'disabled' )
        {
            if ( $redirUri == "enabled" )
            {
                $automaticRedirect = false;
            }
            else
            {
                $uri = eZURI::instance( eZSys::requestURI() );
                $uri->toBeginning();
                foreach ( $ini->variableArray( "DebugSettings", "DebugRedirection" ) as $redirUri )
                {
                    $redirUri = new eZURI( $redirUri );
                    if ( $redirUri->matchBase( $uri ) )
                    {
                        $automaticRedirect = false;
                        break;
                    }
                }
            }
        }

        $redirectURI = eZSys::indexDir();

        $moduleRedirectUri = $this->module->redirectURI();
        if ( $ini->variable( 'URLTranslator', 'Translation' ) === 'enabled' &&
                eZURLAliasML::urlTranslationEnabledByUri( new eZURI( $moduleRedirectUri ) ) )
        {
            $translatedModuleRedirectUri = $moduleRedirectUri;
            if ( eZURLAliasML::translate( $translatedModuleRedirectUri, true ) )
            {
                $moduleRedirectUri = $translatedModuleRedirectUri;
                if ( strlen( $moduleRedirectUri ) > 0 && $moduleRedirectUri[0] !== '/' )
                    $moduleRedirectUri = '/' . $moduleRedirectUri;
            }
        }

        if ( preg_match( '#^(\w+:)|^//#', $moduleRedirectUri ) )
        {
            $redirectURI = $moduleRedirectUri;
        }
        else
        {
            $leftSlash = strlen( $redirectURI ) > 0 && $redirectURI[strlen( $redirectURI ) - 1] === '/';
            $rightSlash = strlen( $moduleRedirectUri ) > 0 && $moduleRedirectUri[0] === '/';

            if ( !$leftSlash && !$rightSlash ) // Both are without a slash, so add one
                $moduleRedirectUri = '/' . $moduleRedirectUri;
            else if ( $leftSlash && $rightSlash ) // Both are with a slash, so we remove one
                $moduleRedirectUri = substr( $moduleRedirectUri, 1 );

            // In some cases $moduleRedirectUri can already contain $redirectURI (including the siteaccess).
            if ( !empty( $redirectURI ) && strpos( $moduleRedirectUri, $redirectURI ) === 0 )
            {
                $redirectURI = $moduleRedirectUri;
            }
            else
            {
                $redirectURI .= $moduleRedirectUri;
            }
        }

        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            $staticCacheHandlerClassName = $ini->variable( 'ContentSettings', 'StaticCacheHandler' );
            $staticCacheHandlerClassName::executeActions();
        }

        eZDB::checkTransactionCounter();

        if ( $automaticRedirect )
        {
            eZHTTPTool::redirect( $redirectURI, array(), $this->module->redirectStatus() );
        }
        else
        {
            // Make sure any errors or warnings are reported
            if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) === 'enabled' )
            {
                if ( isset( $GLOBALS['eZDebugError'] ) && $GLOBALS['eZDebugError'] )
                {
                    eZAppendWarningItem(
                        array(
                            'error' => array(
                                'type' => 'error',
                                'number' => 1,
                                'count' => $GLOBALS['eZDebugErrorCount']
                            ),
                            'identifier' => 'ezdebug-first-error',
                            'text' => ezpI18n::tr( 'index.php', 'Some errors occurred, see debug for more information.' )
                        )
                    );
                }

                if ( isset( $GLOBALS['eZDebugWarning'] ) && $GLOBALS['eZDebugWarning'] )
                {
                    eZAppendWarningItem(
                        array(
                            'error' => array(
                                'type' => 'warning',
                                'number' => 1,
                                'count' => $GLOBALS['eZDebugWarningCount']
                            ),
                            'identifier' => 'ezdebug-first-warning',
                            'text' => ezpI18n::tr( 'index.php', 'Some general warnings occured, see debug for more information.' )
                        )
                    );
                }
            }

            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'site', $this->site );
            $tpl->setVariable( 'warning_list', !empty( $this->warningList ) ? $this->warningList : false );
            $tpl->setVariable( 'redirect_uri', eZURI::encodeURL( $redirectURI ) );
            $templateResult = $tpl->fetch( 'design:redirect.tpl' );

            eZDebug::addTimingPoint( "Script end" );

            eZDisplayResult( $templateResult );
        }

        eZExecution::cleanExit();
    }

    /**
     * Initializes the session. If running, through Symfony the session
     * parameters from Symfony override the session parameter from eZ Publish.
     */
    protected function sessionInit()
    {
        if ( !isset( $this->settings['session'] ) || !$this->settings['session']['configured'] )
        {
            // running without Symfony2 or session is not configured
            // we keep the historic behaviour
            $ini = eZINI::instance();
            if ( $ini->variable( 'Session', 'ForceStart' ) === 'enabled' )
                eZSession::start();
            else
                eZSession::lazyStart();
        }
        else
        {
            $sfHandler = new ezpSessionHandlerSymfony(
                $this->settings['session']['has_previous']
                    || $this->settings['session']['started']
            );
            $sfHandler->setStorage( $this->settings['session']['storage'] );
            eZSession::init(
                $this->settings['session']['name'],
                $this->settings['session']['started'],
                $this->settings['session']['namespace'],
                $sfHandler
            );
        }

        // let session specify if db is required
        $this->siteBasics['db-required'] = eZSession::getHandlerInstance()->dbRequired();
    }

    protected function requestInit()
    {
        if ( $this->isInitialized )
            return;

        eZExecution::setCleanExit( false );
        $scriptStartTime = microtime( true );

        $GLOBALS['eZRedirection'] = false;
        $this->access = eZSiteAccess::current();

        eZDebug::setScriptStart( $scriptStartTime );

        eZDebug::addTimingPoint( "Script start" );

        $this->uri = eZURI::instance( eZSys::requestURI() );
        $GLOBALS['eZRequestedURI'] = $this->uri;

        // Be able to do general events early in process
        ezpEvent::getInstance()->notify( 'request/preinput', array( $this->uri ) );

        // Initialize module loading
        $this->siteBasics['module-repositories'] = eZModule::activeModuleRepositories();
        eZModule::setGlobalPathList( $this->siteBasics['module-repositories'] );

        // make sure we get a new $ini instance now that it has been reset
        $ini = eZINI::instance();

        // pre check, setup wizard related so needs to be before session/db init
        // TODO: Move validity check in the constructor? Setup is not meant to be launched at each (sub)request is it?
        if ( $ini->variable( 'SiteAccessSettings', 'CheckValidity' ) === 'true' )
        {
            $this->check = array( 'module' => 'setup', 'function' => 'init' );
            // Turn off some features that won't bee needed yet
            $this->siteBasics['policy-check-omit-list'][] = 'setup';
            $this->siteBasics['show-page-layout'] = $ini->variable( 'SetupSettings', 'PageLayout' );
            $this->siteBasics['validity-check-required'] = true;
            $this->siteBasics['session-required'] = $this->siteBasics['user-object-required'] = false;
            $this->siteBasics['db-required'] = $this->siteBasics['no-cache-adviced'] = $this->siteBasics['url-translator-allowed'] = false;
            $this->siteBasics['site-design-override'] = $ini->variable( 'SetupSettings', 'OverrideSiteDesign' );
            $this->access = eZSiteAccess::change( array( 'name' => 'setup', 'type' => eZSiteAccess::TYPE_URI ) );
            eZTranslatorManager::enableDynamicTranslations();
        }

        if ( $this->siteBasics['session-required'] )
        {
            // Check if this should be run in a cronjob
            if ( $ini->variable( 'Session', 'BasketCleanup' ) !== 'cronjob' )
            {
                eZSession::addCallback(
                    'destroy_pre',
                    function ( eZDBInterface $db, $key, $escapedKey )
                    {
                        $basket = eZBasket::fetch( $key );
                        if ( $basket instanceof eZBasket )
                            $basket->remove();
                    }
                );
                eZSession::addCallback(
                    'gc_pre',
                    function ( eZDBInterface $db, $time )
                    {
                        eZBasket::cleanupExpired( $time );
                    }
                );

                eZSession::addCallback(
                    'cleanup_pre',
                    function ( eZDBInterface $db )
                    {
                        eZBasket::cleanup();
                    }
                );
            }

            // addCallBack to update session id for shop basket on session regenerate
            eZSession::addCallback(
                'regenerate_post',
                function ( eZDBInterface $db, $escNewKey, $escOldKey  )
                {
                    $db->query( "UPDATE ezbasket SET session_id='{$escNewKey}' WHERE session_id='{$escOldKey}'" );
                }
            );

            // TODO: Session starting should be made only once in the constructor
            $this->sessionInit();
        }

        // if $this->siteBasics['db-required'], open a db connection and check that db is connected
        if ( $this->siteBasics['db-required'] && !eZDB::instance()->isConnected() )
        {
            $this->warningList[] = array(
                'error' => array(
                    'type' => 'kernel',
                    'number' => eZError::KERNEL_NO_DB_CONNECTION
                ),
                'text' => 'No database connection could be made, the system might not behave properly.'
            );
        }

        // pre check, RequireUserLogin & FORCE_LOGIN related so needs to be after session init
        if ( !isset( $this->check ) )
        {
            $this->check = eZUserLoginHandler::preCheck( $this->siteBasics, $this->uri );
        }

        ezpEvent::getInstance()->notify( 'request/input', array( $this->uri ) );

        // Initialize with locale settings
        // TODO: Move to constructor? Is it relevant to init the locale/charset for each (sub)requests?
        $this->languageCode = eZLocale::instance()->httpLocaleCode();
        $phpLocale = trim( $ini->variable( 'RegionalSettings', 'SystemLocale' ) );
        if ( $phpLocale != '' )
        {
            setlocale( LC_ALL, explode( ',', $phpLocale ) );
        }

        $this->httpCharset = eZTextCodec::httpCharset();

        // TODO: are these parameters supposed to vary across potential sub-requests?
        $this->site = array(
            'title' => $ini->variable( 'SiteSettings', 'SiteName' ),
            'design' => $ini->variable( 'DesignSettings', 'SiteDesign' ),
            'http_equiv' => array(
                'Content-Type' => 'text/html; charset=' . $this->httpCharset,
                'Content-language' => $this->languageCode
            )
        );

        // Read role settings
        $this->siteBasics['policy-check-omit-list'] = array_merge(
            $this->siteBasics['policy-check-omit-list'],
            $ini->variable( 'RoleSettings', 'PolicyOmitList' )
        );

        $this->isInitialized = true;

        /**
         * Check for activating Debug by user ID (Final checking. The first was in eZDebug::updateSettings())
         * @uses eZUser::instance() So needs to be executed after eZSession::start()|lazyStart()
         */
        eZDebug::checkDebugByUser();
    }

    /**
     * Run a callback function in legacy environment
     */
    public function runCallback( \Closure $callback, $postReinitialize = true )
    {
        $this->requestInit();

        $return = $callback();

        $this->shutdown( $postReinitialize );

        return $return;
    }

    /**
     * Runs the shutdown process
     */
    protected function shutdown( $reInitialize = true )
    {
        eZExecution::cleanup();
        eZExecution::setCleanExit();
        if ( $reInitialize )
            $this->isInitialized = false;
    }

    /**
     * Sets whether to use exceptions in legacy kernel.
     *
     * @param bool $useExceptions
     */
    public function setUseExceptions( $useExceptions )
    {
        eZModule::$useExceptions = (bool)$useExceptions;
    }

    /**
     * Reinitializes the kernel environment.
     */
    public function reInitialize()
    {
        $this->isInitialized = false;
    }

    /**
     * Checks whether the kernel handler has the Symfony service container
     * container or not.
     *
     * @return bool
     */
    public function hasServiceContainer()
    {
        return isset( $this->settings['service-container'] );
    }

    /**
     * Returns the Symfony service container if it has been injected,
     * otherwise returns null.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface|null
     */
    public function getServiceContainer()
    {
        return $this->settings['service-container'];
    }

    /**
     * Allows user to avoid executing the pagelayout template when running the kernel
     *
     * @param bool $usePagelayout
     */
    public function setUsePagelayout( $usePagelayout )
    {
        $this->siteBasics['show-page-layout'] = (bool)$usePagelayout;
    }
}
