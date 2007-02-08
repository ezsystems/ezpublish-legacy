<?php
/* Removing unused keywords */
$sql = "SELECT ezkeyword.id as id FROM ezkeyword
        LEFT JOIN ezkeyword_attribute_link ON ezkeyword.id=ezkeyword_attribute_link.keyword_id
        WHERE ezkeyword_attribute_link.keyword_id IS NULL";

$keywordArray = $db->arrayQuery( $sql );
$keywordIDArray = array();
foreach ( $keywordArray as $keywordItem )
{
    $keywordIDArray[] = $keywordItem['id'];
}

$totalKeywords = count ( $keywordIDArray );
if ( $totalKeywords > 0 )
{
    eZDebug::writeDebug ( "Deleting $totalKeywords keywords which aren't used by any attribute ...", "keyword-check" );
    
    $keywordString = implode ( ",", $keywordIDArray );
    $sql = "DELETE FROM ezkeyword WHERE id IN ( $keywordString )";
    $db->query ( $sql );
}
else
    eZDebug::writeDebug ( "No unused keywords found ...", "keyword-check");

/* Remove unused entries from ezurl_object_link */
$sql = "SELECT eol.contentobject_attribute_id as co_id, ecz.id
        FROM ezurl_object_link eol LEFT JOIN ezcontentobject_attribute ecz ON (ecz.id = eol.contentobject_attribute_id)
        WHERE id IS NULL";

$urlArray = $db->arrayQuery( $sql );
$urlIDArray = array();
foreach ( $urlArray as $urlItem )
{
    $urlIDArray[] = $urlItem['co_id'];
}

$totalUrls = count ( $urlIDArray );
if ( $totalUrls > 0 )
{
    eZDebug::writeDebug ( "Deleting $totalUrls url-links which aren't used by any attribute ...", "url-check" );
    
    $urlString = implode ( ",", $urlIDArray );
    $sql = "DELETE FROM ezurl_object_link WHERE contentobject_attribute_id IN ( $urlString )";
    $db->query ( $sql );
}
else
    eZDebug::writeDebug ( "No unused url-links found ...", "url-check");

?>
