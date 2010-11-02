<?php

class ezpTemplateHtmlFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Returns the input string with the addresses replaced by link tags.
     */
    public static function autolink($text, $maxChars = null)
    {
        return AutoLink::getLink($text, $maxChars);
    }

    /**
     * Returns a partially marked up version of the input string.
     */
    public static function simpletags($text, $tagListName = false)
    {
        return SimpleTags::getSimpletag($text, $tagListName);
    }

    /**
     * Fetches a subtree of nodes for the purpose of menu generation.
     */
    public static function treemenu($path, $nodeID = false, $classFilter = false, $depthSkip = false, $maxLevel = false, $isSelectedMethod = 'tree', $indentationLevel = 15, $language = false)
    {
        return TreeMenu::getTreeMenu($path, $classFilter, $depthSkip, $maxLevel, $isSelectedMethod, $indentationLevel, $language);
    }

    public static function topmenu($context = 'content')
    {
        return TopMenu::getTopMenu($context);
    }

    public static function content_structure_tree($rootNodeID, $classFilter = false, $maxDepth = 0, $maxNodes = 0, $sortBy = false, $fetchHidden = false, $unfoldNodeID = 0)
    {
        switch( $fetchHidden )
        {
            case 'false': $fetchHidden = false; break;
            case 'true': $fetchHidden = true; break;
        }

        switch( $sortBy )
        {
            case 'false': $sortBy = false; break;
        }

        return ContentStructureTree::getContentStructureTree($rootNodeID, $classFilter, $maxDepth, $maxNodes, $sortBy, $fetchHidden, $unfoldNodeID);
    }

    public static function eztoc( $contentObjectAttribute )
    {
        $toc = new ezpTOC( $contentObjectAttribute );
        return $toc->generate();
    }
}

?>
