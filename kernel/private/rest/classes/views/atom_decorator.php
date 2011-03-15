<?php
/**
 * File containing the ezpRestAtomDecorator
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestAtomDecorator extends ezpRestFeedDecorator
{
    public function decorateFeed( ezcFeed $feed )
    {
        $feed->generator = "eZ Publish";
        $feed->updated = time();
        $feed->author = "The Editor.";
        $feed->id = "node:f3e90596361e31d496d4026eb624c983:2";
        $feed->title = "eZ Publish REST content sync feed for [Collection]";
    }

    /**
     * Returns the name of the variable in the result object to decorate
     *
     * @return string
     */
    public function getItemVariable()
    {
        return 'collection';
    }

    /**
     * Adds feed metadata pertaining to the item's data specified in $data
     *
     * @todo Add list of required metadata to add
     *
     * @param string $ezcFeedEntryElement
     * @param string $data
     * @return void
     */
    public function decorateFeedItem( ezcFeedEntryElement $item, $data )
    {
        $author = $item->add( 'author' );
        $author->name = $data['author'];

        $link = $item->add( 'link' );
        $link->href = $data['nodeUrl'];

        $item->title = $data['objectName'];
        $item->updated = $data['modified'];
        $item->published = $data['published'];
        $item->id = "N/A";
        $item->description = "";
    }

}
?>
