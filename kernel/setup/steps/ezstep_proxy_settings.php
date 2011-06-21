<?php
/**
 * File containing the eZStepProxySettings class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version  2011.5
 * @package kernel
 */

/*!
  \class eZStepProxySettings ezstep_proxy_settings.php
  \brief The class eZStepProxySettings does

*/

class eZStepProxySettings extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepProxySettings( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'proxy_settings', 'Proxy settings' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetupPROXYServer' ) )
        {
            $this->PersistenceList['proxy_info']['server'] = $this->Http->postVariable( 'eZSetupPROXYServer' );
            $this->PersistenceList['proxy_info']['user'] = $this->Http->postVariable( 'eZSetupPROXYUser' );
            $this->PersistenceList['proxy_info']['password'] = $this->Http->postVariable( 'eZSetupPROXYPassword' );
            $siteINI = eZINI::instance( 'site.ini.append', 'settings/override', null, null, false, true );
            $siteINI->setVariable( 'ProxySettings', 'ProxyServer', $this->PersistenceList['proxy_info']['server'] );
            $siteINI->setVariable( 'ProxySettings', 'User', $this->PersistenceList['proxy_info']['user'] );
            $siteINI->setVariable( 'ProxySettings', 'Password', $this->PersistenceList['proxy_info']['password'] );
            $siteINI->save( 'site.ini.append', '.php', false, false );
        }

        return true;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();
            $this->PersistenceList['proxy_info']['server'] = $data['Server'];
            $this->PersistenceList['proxy_info']['user'] = $data['User'];
            $this->PersistenceList['proxy_info']['password'] = $data['Password'];
            eZSetupMergePersistenceList( $this->PersistenceList, $persistenceData );
            return $this->kickstartContinueNextStep();
        }
        return false; // Always display proxy settings
    }

    function display()
    {
        $proxyInfo = array( 'server' => false,
                            'user' => false,
                            'password' => false );
        if ( isset( $this->PersistenceList['proxy_info'] ) )
            $proxyInfo = array_merge( $proxyInfo, $this->PersistenceList['proxy_info'] );
        if ( $proxyInfo['server'] and
             $this->Ini->variable( 'ProxySettings', 'ProxyServer' ) )
            $proxyInfo['server'] = $this->Ini->variable( 'ProxySettings', 'ProxyServer' );
        if ( $proxyInfo['user'] and
             $this->Ini->variable( 'ProxySettings', 'TransportUser' ) )
            $proxyInfo['user'] = $this->Ini->variable( 'ProxySettings', 'User' );
        if ( $proxyInfo['password'] and
             $this->Ini->variable( 'ProxySettings', 'TransportPassword' ) )
            $proxyInfo['password'] = $this->Ini->variable( 'ProxySettings', 'Password' );
        $this->Tpl->setVariable( 'proxy_info', $proxyInfo );
        $this->Tpl->setVariable( 'setup_previous_step', 'EmailSettings' );
        $this->Tpl->setVariable( 'setup_next_step', 'DatabaseCreate' );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/proxy_settings.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'proxy settings' ),
                                        'url' => false ) );
        return $result;

    }
}

?>