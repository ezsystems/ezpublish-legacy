<?php
// implementation = ezmysql
// server = bf.ez.no
// database = intranet

function eZURLAliasWilcardTranslate( &$uri, &$urlAlias )
{
    if ( preg_match( "#images/(.*)#", $uri, $matches ) )
    {
        $uri = "media/images/" . $matches[1] . "";
        $urlAlias = array( "id" => "82",
                           "source_url" => "images/*",
                           "source_md5" => "04e9ea07da46830b94f38285ba6ea065",
                           "destination_url" => "media/images/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    return false;
}
?>
