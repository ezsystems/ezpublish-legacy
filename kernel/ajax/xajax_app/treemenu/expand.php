<?php

function expandTreeNode( $nodeID, $UIcontext )
{
    $objResponse = new xajaxResponse();

    include_once( 'kernel/common/template.php' );

    if (!isset( $nodeID )) {
        $objResponse->addAlert( 'An error occured. Node id not found.' );
        return $objResponse->getXML();
    }

    $tpl =& templateInit();

    if ( isset( $UIcontext ) )
    {
         $tpl->setVariable( 'ui_context', $UIcontext );
         $Result['ui_context'] = $UIcontext;
         if ( $UIcontext == "browse" )
         {
             $tpl->setVariable( 'csm_menu_item_click_action', '/content/browse' );
         }
    }
    $tpl->setVariable( 'nodeID', $nodeID );

    $content = $tpl->fetch( 'design:contentstructuremenu/expand_dynamic_content_structure.tpl' );
//    $objResponse->addAlert( $content );
    $content = str_replace( "\n", "", $content );
    include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    $charset = 'UTF-8';
    $codec =& eZTextCodec::instance( false, $charset );
    $content = $codec->convertString( $content );
                                                    
    $objResponse->addScript( 'odcsm_expand_element.innerHTML="'.addslashes($content).'"');
    $objResponse->addScript( 'ezcst_foldUnfoldSubtree( odcsm_expand_element_parent, false, true, true, true, true );');

    return $objResponse->getXML();
}

?>
