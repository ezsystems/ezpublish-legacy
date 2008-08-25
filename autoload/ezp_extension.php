<?php
/**
 * Autoloader definition for eZ Publish extension files.
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 *
 */

return array(
      'JsonContent'                    => 'extension/ezflow/classes/json_content.php',
      'PclZip'                         => 'extension/ezodf/lib/pclzip.lib.php',
      'eZArchive'                      => 'extension/ezwebin/autoloads/ezarchive.php',
      'eZCreateClassListGroups'        => 'extension/ezwebin/autoloads/ezcreateclasslistgroups.php',
      'eZDHTMLInputParser'             => 'extension/ezdhtml/ezxmltext/handlers/input/ezdhtmlinputparser.php',
      'eZDHTMLXMLInput'                => 'extension/ezdhtml/ezxmltext/handlers/input/ezdhtmlxmlinput.php',
      'eZFlowFetchInterface'           => 'extension/ezflow/classes/ezflowfetchinterface.php',
      'eZFlowFunctionCollection'       => 'extension/ezflow/modules/ezflow/functioncollection.php',
      'eZFlowLatestObjects'            => 'extension/ezflow/classes/fetches/ezflowlatestobjects.php',
      'eZFlowOperations'               => 'extension/ezflow/classes/ezflowoperations.php',
      'eZFlowPool'                     => 'extension/ezflow/classes/ezflowpool.php',
      'eZKeywordList'                  => 'extension/ezwebin/autoloads/ezkeywordlist.php',
      'eZOOConverter'                  => 'extension/ezodf/modules/ezodf/ezooconverter.php',
      'eZOOGenerator'                  => 'extension/ezodf/modules/ezodf/ezoogenerator.php',
      'eZOOImport'                     => 'extension/ezodf/modules/ezodf/ezooimport.php',
      'eZOpenofficeUploadHandler'      => 'extension/ezodf/uploadhandlers/ezopenofficeuploadhandler.php',
      'eZPage'                         => 'extension/ezflow/classes/ezpage.php',
      'eZPageBlock'                    => 'extension/ezflow/classes/ezpageblock.php',
      'eZPageBlockItem'                => 'extension/ezflow/classes/ezpageblockitem.php',
      'eZPageType'                     => 'extension/ezflow/datatypes/ezpage/ezpagetype.php',
      'eZPageZone'                     => 'extension/ezflow/classes/ezpagezone.php',
      'eZRed5StreamListOperator'       => 'extension/ezflow/autoloads/ezred5streamlist.php',
      'eZSquidCacheManager'            => 'extension/ezflow/classes/ezsquidcachemanager.php',
      'eZTagCloud'                     => 'extension/ezwebin/autoloads/eztagcloud.php',
      'eZUnserialize'                  => 'extension/ezflow/autoloads/ezunserialize.php',
      'ezdhtmlInfo'                    => 'extension/ezdhtml/ezinfo.php',
      'ezflowInfo'                     => 'extension/ezflow/ezinfo.php',
      'ezodfInfo'                      => 'extension/ezodf/ezinfo.php',
      'ezpMigratedUrlAlias'            => 'extension/ezurlaliasmigration/classes/ezpmigratedurlalias.php',
      'ezpUrlAliasController'          => 'extension/ezurlaliasmigration/classes/ezpurlaliascontroller.php',
      'ezpUrlAliasHistoryController'   => 'extension/ezurlaliasmigration/classes/ezpurlaliashistorycontroller.php',
      'ezpUrlAliasMigrateTool'         => 'extension/ezurlaliasmigration/classes/ezpurlaliasmigratetool.php',
      'ezpUrlAliasMigrationController' => 'extension/ezurlaliasmigration/classes/ezpurlaliasmigrationcontroller.php',
      'ezpUrlAliasPathWalker'          => 'extension/ezurlaliasmigration/classes/ezpurlaliaspathwalker.php',
      'ezpUrlAliasQueryStrict'         => 'extension/ezurlaliasmigration/classes/ezpurlaliasquerystrict.php',
      'ezurlaliasInfo'                 => 'extension/ezurlaliasmigration/ezinfo.php',
      'ezwebinInfo'                    => 'extension/ezwebin/ezinfo.php',
    );

?>