<?php
// implementation = ezmysql
// server = bf.ez.no
// database = news

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
    else if ( preg_match( "#discussions/this_is_a_new_topic/(.*)#", $uri, $matches ) )
    {
        $uri = "discussions/music_discussion/this_is_a_new_topic/" . $matches[1] . "";
        $urlAlias = array( "id" => "106",
                           "source_url" => "discussions/this_is_a_new_topic/*",
                           "source_md5" => "3597b3c74225331ec401c8abc9f6d1d4",
                           "destination_url" => "discussions/music_discussion/this_is_a_new_topic/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    else if ( preg_match( "#forum/(.*)#", $uri, $matches ) )
    {
        $uri = "discussions/" . $matches[1] . "";
        $urlAlias = array( "id" => "113",
                           "source_url" => "forum/*",
                           "source_md5" => "94b1ef84913dabe113cb907c181ee300",
                           "destination_url" => "discussions/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    else if ( preg_match( "#discussions/music_discussion/(.*)#", $uri, $matches ) )
    {
        $uri = "discussions/forum_main_group/music_discussion/" . $matches[1] . "";
        $urlAlias = array( "id" => "150",
                           "source_url" => "discussions/music_discussion/*",
                           "source_md5" => "2ec2a3bfcf01ad3f1323390ab26dfeac",
                           "destination_url" => "discussions/forum_main_group/music_discussion/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    else if ( preg_match( "#discussions/sports_discussion/(.*)#", $uri, $matches ) )
    {
        $uri = "discussions/forum_main_group/sports_discussion/" . $matches[1] . "";
        $urlAlias = array( "id" => "152",
                           "source_url" => "discussions/sports_discussion/*",
                           "source_md5" => "7acbf48218ca6e1d80c267911860d34f",
                           "destination_url" => "discussions/forum_main_group/sports_discussion/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    else if ( preg_match( "#news/breaking_news/(.*)#", $uri, $matches ) )
    {
        $uri = "news/politics/breaking_news/" . $matches[1] . "";
        $urlAlias = array( "id" => "195",
                           "source_url" => "news/breaking_news/*",
                           "source_md5" => "1c84412822823453849fbfd423dce066",
                           "destination_url" => "news/politics/breaking_news/{1}",
                           "is_internal" => "1",
                           "is_wildcard" => "1",
                           "forward_to_id" => "0" );
        return true;
    }
    return false;
}
?>
