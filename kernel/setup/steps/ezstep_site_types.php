<?php
//
// Definition of eZStepSiteTypes class
//
// Created on: <16-Apr-2004 09:56:02 amos>
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



/*!
  \class eZStepSiteTypes ezstep_site_types.php
  \brief The class eZStepSiteTypes does

*/

class eZStepSiteTypes extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteTypes( $tpl, $http, $ini, &$persistenceList )
    {
        $ini = eZINI::instance( 'package.ini' );
        $indexURL = trim( $ini->variable( 'RepositorySettings', 'RemotePackagesIndexURL' ) );
        if ( $indexURL === '' )
        {
            $indexURL = trim( $ini->variable( 'RepositorySettings', 'RemotePackagesIndexURLBase' ) );
            if ( substr( $indexURL, -1, 1 ) !== '/' )
            {
                $indexURL .= '/';
            }
            $indexURL .= eZPublishSDK::version( false, false, false ) . '/' . eZPublishSDK::version() . '/';
        }
        $this->IndexURL = $indexURL;

        if ( substr( $this->IndexURL, -1, 1 ) == '/' )
            $this->XMLIndexURL = $this->IndexURL . 'index.xml';
        else
            $this->XMLIndexURL = $this->IndexURL . '/index.xml';

        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_types', 'Site types' );
    }

    /**
     * Downloads file.
     *
     * Sets $this->ErrorMsg in case of an error.
     *
     * \private
     * \param $url            URL.
     * \param $outDir         Directory where to put downloaded file to.
     * \param $forcedFileName Force saving downloaded file under this name.
     * \return false on error, path to downloaded package otherwise.
     */
    function downloadFile( $url, $outDir, $forcedFileName = false )
    {
        $fileName = $outDir . "/" . ( $forcedFileName ? $forcedFileName : basename( $url ) );

        eZDebug::writeNotice( "Downloading file '$fileName' from $url" );

        // Create the out directory if not exists.
        if ( !file_exists( $outDir ) )
            eZDir::mkdir( $outDir, false, true );

        // First try CURL
        if ( extension_loaded( 'curl' ) )
        {
            $ch = curl_init( $url );
            $fp = eZStepSiteTypes::fopen( $fileName, 'wb' );

            if ( $fp === false )
            {
                $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init', 'Cannot write to file' ) .
                    ': ' . $this->FileOpenErrorMsg;
                return false;
            }

            curl_setopt( $ch, CURLOPT_FILE, $fp );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );
            curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
            // Get proxy
            $ini = eZINI::instance();
            $proxy = $ini->hasVariable( 'ProxySettings', 'ProxyServer' ) ? $ini->variable( 'ProxySettings', 'ProxyServer' ) : false;
            if ( $proxy )
            {
                curl_setopt ( $ch, CURLOPT_PROXY , $proxy );
                $userName = $ini->hasVariable( 'ProxySettings', 'User' ) ? $ini->variable( 'ProxySettings', 'User' ) : false;
                $password = $ini->hasVariable( 'ProxySettings', 'Password' ) ? $ini->variable( 'ProxySettings', 'Password' ) : false;
                if ( $userName )
                {
                    curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, "$userName:$password" );
                }
            }

            if ( !curl_exec( $ch ) )
            {
                $this->ErrorMsg = curl_error( $ch );
                return false;
            }

            curl_close( $ch );
            fclose( $fp );
        }
        else
        {
            $parsedUrl = parse_url( $url );
            $checkIP = isset( $parsedUrl[ 'host' ] ) ? ip2long( gethostbyname( $parsedUrl[ 'host' ] ) ) : false;
            if ( $checkIP === false )
            {
                return false;
            }

            // If we don't have CURL installed we used standard fopen urlwrappers
            // Note: Could be blocked by not allowing remote calls.
            if ( !copy( $url, $fileName ) )
            {
                $buf = eZHTTPTool::sendHTTPRequest( $url, 80, false, 'eZ Publish', false );

                $header = false;
                $body = false;
                if ( eZHTTPTool::parseHTTPResponse( $buf, $header, $body ) )
                {
                    eZFile::create( $fileName, false, $body );
                }
                else
                {
                    $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init', 'Failed to copy %url to local file %filename', null,
                                              array( "%url" => $url,
                                                     "%filename" => $fileName ) );
                    return false;
                }
            }
        }

        return $fileName;
    }

    /**
     * Downloads and imports package.
     *
     * Sets $this->ErrorMsg in case of an error.
     *
     * \param $forceDownload  download even if this package already exists.
     * \private
     * \return false on error, package object otherwise.
     */
    function downloadAndImportPackage( $packageName, $packageUrl, $forceDownload = false )
    {
        $package = eZPackage::fetch( $packageName, false, false, false );

        if ( is_object( $package ) )
        {
            if ( $forceDownload )
            {
                $package->remove();
            }
            else
            {
                eZDebug::writeNotice( "Skipping download of package '$packageName': package already exists." );
                return $package;
            }
        }

        $archiveName = $this->downloadFile( $packageUrl, /* $outDir = */ eZStepSiteTypes::tempDir() );
        if ( $archiveName === false )
        {
            eZDebug::writeWarning( "Download of package '$packageName' from '$packageUrl' failed: $this->ErrorMsg" );
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'Download of package \'%pkg\' failed. You may upload the package manually.',
                                      false, array( '%pkg' => $packageName ) );

            return false;
        }

        $package = eZPackage::import( $archiveName, $packageName, false );

        // Remove downloaded ezpkg file
        eZFileHandler::unlink( $archiveName );

        if ( !$package instanceof eZPackage )
        {
            if ( $package == eZPackage::STATUS_INVALID_NAME )
            {
                eZDebug::writeNotice( "The package name $packageName is invalid" );
            }
            else
            {
                eZDebug::writeNotice( "Invalid package" );
            }

            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init', 'Invalid package' );
            return false;
        }

        return $package;
    }


    /*!
     * Download packages required by the given package.
     *
     * \private
     */
    function downloadDependantPackages( $sitePackage )
    {
        $dependencies = $sitePackage->attribute( 'dependencies' );
        $requirements = $dependencies['requires'];
        $remotePackagesInfo = $this->retrieveRemotePackagesList();

        foreach ( $requirements as $req )
        {
            $requiredPackageName = $req['name'];

            if ( isset( $req['min-version'] ) )
                $requiredPackageVersion = $req['min-version'];
            else
                $requiredPackageVersion = 0;

            $downloadNewPackage   = false;
            $removeCurrentPackage = false;

            // try to fetch the required package
            $package = eZPackage::fetch( $requiredPackageName, false, false, false );

            // if it already exists
            if ( is_object( $package ) )
            {
                // check its version
                $currentPackageVersion = $package->getVersion();

                // if existing package's version is less than required one
                // we remove the package and download newer one.

                if ( version_compare( $currentPackageVersion, $requiredPackageVersion ) < 0 )
                {
                    $downloadNewPackage   = true;
                    $removeCurrentPackage = true;
                }

                // else (if the version is greater or equal to the required one)
                // then do nothing (skip downloading)
            }
            else
                // if the package does not exist, we download it.
                $downloadNewPackage   = true;

            if ( $removeCurrentPackage )
            {
                $package->remove();
                unset( $package );
            }

            if ( $downloadNewPackage )
            {
                if ( !isset( $remotePackagesInfo[$requiredPackageName]['url'] ) )
                {
                    eZDebug::writeWarning( "Download of package '$requiredPackageName' failed: the URL is unknown." );
                    $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                              'Download of package \'%pkg\' failed. You may upload the package manually.',
                                              false, array( '%pkg' => $requiredPackageName ) );
                    $this->ShowURL = true;

                    return false;
                }

                $requiredPackageURL = $remotePackagesInfo[$requiredPackageName]['url'];
                $rc = $this->downloadAndImportPackage( $requiredPackageName, $requiredPackageURL );
                if( !is_object( $rc ) )
                {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Upload local package.
     *
     * \private
     */
    function uploadPackage()
    {

        if ( !eZHTTPFile::canFetch( 'PackageBinaryFile' ) )
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'No package selected for upload' ) . '.';
            return;
        }

        $file = eZHTTPFile::fetch( 'PackageBinaryFile' );
        if ( !$file )
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'Failed fetching upload package file' );
            return;
        }

        $packageFilename = $file->attribute( 'filename' );
        $packageName = $file->attribute( 'original_filename' );
        if ( preg_match( "#^(.+)-[0-9](\.[0-9]+)-[0-9].ezpkg$#", $packageName, $matches ) )
            $packageName = $matches[1];
        $packageName = preg_replace( array( "#[^a-zA-Z0-9]+#",
                                            "#_+#",
                                            "#(^_)|(_$)#" ),
                                     array( '_',
                                            '_',
                                            '' ), $packageName );
        $package = eZPackage::import( $packageFilename, $packageName, false );

        if ( is_object( $package ) )
        {
            // package successfully imported
            return;
        }
        elseif ( $package == eZPackage::STATUS_ALREADY_EXISTS )
        {
            eZDebug::writeWarning( "Package '$packageName' already exists." );
        }
        else
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                  'Uploaded file is not an eZ Publish package' );
        }
    }

    /**
     * Process POST data.
     *
     * \reimp
     */
    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'UploadPackageButton' ) )
        {
            $this->uploadPackage();
            return false; // force displaying the same step.
        }

        if ( !$this->Http->hasPostVariable( 'eZSetup_site_type' ) )
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'No site package chosen.' );
            return false;
        }

        $sitePackageInfo = $this->Http->postVariable( 'eZSetup_site_type' );
        $downloaded = false; // true - if $sitePackageName package has been downloaded.
        if ( preg_match( '/^(\w+)\|(.+)$/', $sitePackageInfo, $matches ) )
        {
            // remote site package chosen: download it.
            $sitePackageName = $matches[1];
            $sitePackageURL  = $matches[2];

            // we already know that we should download the package anyway as it has newer version
            // so use force download mode
            $package = $this->downloadAndImportPackage( $sitePackageName, $sitePackageURL, true );
            if ( is_object( $package ) )
            {
                $downloaded = true;
                $this->Message = ezpI18n::tr( 'design/standard/setup/init', 'Package \'%packageName\' and it\'s dependencies have been downloaded successfully. Press \'Next\' to continue.', false, array( '%packageName' => $sitePackageName ) );
            }
        }
        else
        {
            // local (already imported) site package chosen: just fetch it.
            $sitePackageName = $sitePackageInfo;

            $package = eZPackage::fetch( $sitePackageName, false, false, false );
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init', 'Invalid package' ) . '.';
        }

        // Verify package.
        if ( !is_object( $package ) || !$this->selectSiteType( $sitePackageName ) )
            return false;

        // Download packages that the site package requires.
        $downloadDependandPackagesResult = $this->downloadDependantPackages( $package );
        return $downloadDependandPackagesResult == false ? false : !$downloaded;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();
            $remoteSitePackages = $this->retrieveRemoteSitePackagesList();
            $importedSitePackages = $this->fetchAvailableSitePackages();
            $dependenciesStatus = array();

            // check site package dependencies to show their status in the template
            foreach ( $importedSitePackages as $sitePackage )
            {
                $sitePackageName = $sitePackage->attribute( 'name' );
                $dependencies = $sitePackage->attribute( 'dependencies' );
                $requirements = $dependencies['requires'];

                foreach ( $requirements as $req )
                {
                    $requiredPackageName    = $req['name'];
                    $requiredPackageVersion = $req['min-version'];
                    $packageOK = false;

                    $package = eZPackage::fetch( $requiredPackageName, false, false, false );
                    if ( is_object( $package ) )
                    {
                        $currentPackageVersion = $package->getVersion();
                        if ( version_compare( $currentPackageVersion, $requiredPackageVersion ) >= 0 )
                            $packageOK = true;
                    }

                    $dependenciesStatus[$sitePackageName][$requiredPackageName] = array( 'version' => $requiredPackageVersion,
                                                                                     'status'  => $packageOK );
                }
            }

            $sitePackages = $this->createSitePackagesList( $remoteSitePackages, $importedSitePackages, $dependenciesStatus );
            $chosenSitePackage = $data['Site_package'];
            $downloaded = false;
            foreach( $sitePackages as $sitePackagesInfo )
            {
                if( $sitePackagesInfo['name'] == $chosenSitePackage )
                {
                    $sitePackagesInfoChoosen = $sitePackagesInfo;
                }
            }
            if ( isset( $sitePackagesInfoChoosen ) and array_key_exists( 'url', $sitePackagesInfoChoosen ) )
            {
                // we already know that we should download the package anyway as it has newer version
                // so use force download mode
                $package = $this->downloadAndImportPackage( $chosenSitePackage, $sitePackagesInfoChoosen['url'], true );
                if ( is_object( $package ) )
                {

                    $downloadDependandPackagesResult = $this->downloadDependantPackages( $package );
                    if ( $downloadDependandPackagesResult != false )
                    {
                        $downloaded = true;
                    }
                }
            }

            if ( $downloaded and $this->selectSiteType( $chosenSitePackage ) )
            {
                return $this->kickstartContinueNextStep();
            }
        }

        if ( !isset( $this->ErrorMsg ) )
            $this->ErrorMsg = false;

        return false; // Always show site template selection
    }

    /**
     * \private
     */
    function createSitePackagesList( $remoteSitePackages, $importedSitePackages, $dependenciesStatus )
    {
        $sitePackages = array();

        if ( is_array( $remoteSitePackages ) )
        {
            foreach ( $remoteSitePackages as $packageInfo )
            {
                $packageName = $packageInfo['name'];
                $sitePackages[$packageName] = $packageInfo;
            }
        }

        foreach ( $importedSitePackages as $package )
        {
            $packageName = $package->attribute( 'name' );
            $packageVersion = $package->getVersion();

            if ( isset( $sitePackages[$packageName] ) )
            {
                $remoteVersion = $sitePackages[$packageName]['version'];
                $localVersion = $packageVersion;

                if ( version_compare( $remoteVersion, $localVersion ) > 0 )
                    continue;
            }

            $thumbnails = $package->attribute( 'thumbnail-list' );

            $thumbnailPath = false;
            if ( $thumbnails )
            {
                $thumbnailFile = $thumbnails[0];
                $thumbnailPath = $package->fileItemPath( $thumbnailFile, 'default' );
            }

            $dependencies = $package->attribute( 'dependencies' );
            $requirements = $dependencies['requires'];

            $requiresPackageInfo = isset( $dependenciesStatus[$packageName] ) ? $dependenciesStatus[$packageName] : null;
            $packageInfo = array(
                'name' => $packageName,
                'version' => $package->getVersion(),
                'type' => $package->attribute( 'type' ),
                'summary' => $package->attribute( 'summary' ),
                'description' => $package->attribute( 'description' ),
                'requires' => $requiresPackageInfo,
                );

            if ( $thumbnailPath )
                $packageInfo['thumbnail_path'] = $thumbnailPath;

            $sitePackages[$packageName] = $packageInfo;
        }

        // Set availability status for each package.
        foreach ( $sitePackages as $idx => $packageInfo )
            $sitePackages[$idx]['status'] = !isset( $packageInfo['url'] );

        $sortBySummary = create_function('$x,$y', "return \$x['summary'] < \$y['summary'] ? -1 : 1;");
        usort( $sitePackages, $sortBySummary );

        return $sitePackages;
    }

    function display()
    {
        $remoteSitePackages = $this->retrieveRemoteSitePackagesList();
        $importedSitePackages = $this->fetchAvailableSitePackages();
        $dependenciesStatus = array();

        // check site package dependencies to show their status in the template
        foreach ( $importedSitePackages as $sitePackage )
        {
            $sitePackageName = $sitePackage->attribute( 'name' );
            $dependencies = $sitePackage->attribute( 'dependencies' );
            $requirements = $dependencies['requires'];

            foreach ( $requirements as $req )
            {
                $requiredPackageName    = $req['name'];
                $requiredPackageVersion = $req['min-version'];
                $packageOK = false;

                $package = eZPackage::fetch( $requiredPackageName, false, false, false );
                if ( is_object( $package ) )
                {
                    $currentPackageVersion = $package->getVersion();
                    if ( version_compare( $currentPackageVersion, $requiredPackageVersion ) >= 0 )
                        $packageOK = true;
                }

                $dependenciesStatus[$sitePackageName][$requiredPackageName] = array( 'version' => $requiredPackageVersion,
                                                                                     'status'  => $packageOK );
            }
        }

        $sitePackages = $this->createSitePackagesList( $remoteSitePackages, $importedSitePackages, $dependenciesStatus );

        $chosenSitePackage = $this->chosenSitePackage();

        $this->Tpl->setVariable( 'site_packages', $sitePackages );
        $this->Tpl->setVariable( 'dependencies_status', $dependenciesStatus );
        $this->Tpl->setVariable( 'chosen_package', $chosenSitePackage );
        $this->Tpl->setVariable( 'error', $this->ErrorMsg );
        $this->Tpl->setVariable( 'index_url', $this->IndexURL );
        $this->Tpl->setVariable( 'message', $this->Message );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_types.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Site selection' ),
                                        'url' => false ) );
        return $result;
    }

    /**
     * Fetches list of site packages already available locally.
     *
     * \private
     */
    function fetchAvailableSitePackages()
    {
        $packageList = eZPackage::fetchPackages( array( 'db_available' => false ), array( 'type' => 'site' ) );

        return $packageList;
    }

    /**
     * Fetches list of packages already available locally.
     *
     * \private
     */
    function fetchAvailablePackages( $type = false )
    {
        $typeArray  = array();
        if ( $type )
            $typeArray['type'] = $type;

        $packageList = eZPackage::fetchPackages( array( 'db_available' => false ), $typeArray );

        return $packageList;
    }


    /**
     * Retrieve list of packages available to download.
     *
     * Example of return value:
     * array(
     *  'packages' => array(
     *                      '<package_name1>' => array( "name" =>... , "version" =>... , "summary" => ... "url" =>... ),
     *                      '<package_name2>' => array( "name" =>... , "version" =>... , "summary" => ... "url" =>... )
     *                     )
     *      );
     *
     */
    function retrieveRemotePackagesList( $onlySitePackages = false )
    {
        // Download index file.
        $idxFileName = $this->downloadFile( $this->XMLIndexURL, /* $outDir = */ eZStepSiteTypes::tempDir(), 'index.xml' );

        if ( $idxFileName === false )
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'Retrieving remote site packages list failed. ' .
                                      'You may upload packages manually.' );

            eZDebug::writeNotice( "Cannot download remote packages index file from '$this->XMLIndexURL'." );
            return false;
        }

        // Parse it.
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $dom->preserveWhiteSpace = false;
        $success = $dom->load( realpath( $idxFileName ) );

        @unlink( $idxFileName );

        if ( !$success )
        {
            eZDebug::writeError( "Unable to open index file." );
            return false;
        }

        $root = $dom->documentElement;

        if ( $root->localName != 'packages' )
        {
            eZDebug::writeError( "Malformed index file." );
            return false;
        }

        $packageList = array();
        foreach ( $root->childNodes as $packageNode )
        {
            if ( $packageNode->localName != 'package' ) // skip unwanted chilren
                continue;
            if ( $onlySitePackages && $packageNode->getAttribute( 'type' ) != 'site' )  // skip non-site packages
                continue;
            $packageAttributes = array();
            foreach ( $packageNode->attributes as $attributeNode )
            {
                $packageAttributes[$attributeNode->localName] = $attributeNode->value;
            }
            $packageList[$packageAttributes['name']] = $packageAttributes;
        }

        return $packageList;
    }

    /**
     * Retrieve list of site packages available to download.
     * \private
     */
    function retrieveRemoteSitePackagesList()
    {
        return $this->retrieveRemotePackagesList( true );
    }

    /**
     * Wrapper for standard fopen() doing error checking.
     *
     * \private
     * \static
     */
    function fopen( $fileName, $mode )
    {
        $savedTrackErrorsFlag = ini_get( 'track_errors' );
        ini_set( 'track_errors', 1 );

        if ( ( $handle = @fopen( $fileName, 'wb' ) ) === false )
            $this->FileOpenErrorMsg = $php_errormsg;

        ini_set( 'track_errors', $savedTrackErrorsFlag );

        return $handle;
    }

    /**
     * Returns temporary directory used to download files to.
     *
     * \static
     */
    function tempDir()
    {
        return eZDir::path( array( eZSys::cacheDirectory(),
                                    'packages' ) );
    }

    // current repository URL
    public $IndexURL;
    public $XMLIndexURL;

    public $Error = 0;
    public $ErrorMsg = false;
    public $FileOpenErrorMsg = false;
    public $Message = false;
}

?>
