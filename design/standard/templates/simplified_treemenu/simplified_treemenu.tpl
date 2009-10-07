{let classFilter            = ezini( 'TreeMenu', 'ShowClasses'      , 'contentstructuremenu.ini' )
     maxDepth               = ezini( 'TreeMenu', 'MaxDepth'         , 'contentstructuremenu.ini' )
     maxNodes               = ezini( 'TreeMenu', 'MaxNodes'         , 'contentstructuremenu.ini' )
     sortBy                 = ezini( 'TreeMenu', 'SortBy'           , 'contentstructuremenu.ini' )
     fetchHidden            = ezini( 'SiteAccessSettings', 'ShowHiddenNodes', 'site.ini'         )
     itemClickAction        = ezini( 'TreeMenu', 'ItemClickAction'  , 'contentstructuremenu.ini' )
     classIconsSize         = ezini( 'TreeMenu', 'ClassIconsSize'   , 'contentstructuremenu.ini' )
     preloadClassIcons      = ezini( 'TreeMenu', 'PreloadClassIcons', 'contentstructuremenu.ini' )
     autoopenCurrentNode    = ezini( 'TreeMenu', 'AutoopenCurrentNode', 'contentstructuremenu.ini' )

     chapterClasses         = ezini( 'TreeMenu', 'ChapterClasses'   , 'contentstructuremenu.ini' )
     rootNodeID             = false()
     unfoldNodeID           = $module_result.node_id

     contentStructureTree   = false()
     menuID                 = "content_tree_menu"
     isDepthUnlimited       = eq($:maxDepth, 0)
     rootNode               = false }

    {* find root_node_id *}
    {set node=fetch( 'content', 'node', hash('node_id', $module_result.node_id ) )}

    {section show=$chapterClasses|contains($node.object.class_identifier)}
        {set unfoldNodeID=$module_result.node_id}
        {set rootNodeID=$module_result.node_id}
    {section-else}
        {section var=path_node loop=$node.path reverse=true()}
            {if $rootNodeID|not()}
                {if $chapterClasses|contains($path_node.object.class_identifier) }
                    {set rootNodeID=$path_node.node_id}
                {else}
                    {set unfoldNodeID=$path_node.node_id}
                {/if}
            {/if}
        {/section}
        {if $rootNodeID|not()}
            {set rootNodeID=$node.path.0.node_id}
            {set unfoldNodeID=$node.path.1.node_id}
        {/if}
    {/section}

    {set rootNode=fetch( 'content', 'node', hash( node_id, $:rootNodeID ) )}

    {* create menu *}
    {cache-block keys=array($module_result.node_id)}
        {* Fetch content structure. *}
        {set contentStructureTree = content_structure_tree( $:rootNodeID,
                                                            $:classFilter,
                                                            $:maxDepth,
                                                            $:maxNodes,
                                                            $:sortBy,
                                                            $:fetchHidden,
                                                            $:unfoldNodeID ) }
        {* Show menu tree. All container nodes are unfolded. *}
        <ul id="{$:menuID}">
            {include uri="design:simplified_treemenu/show_simplified_menu.tpl" contentStructureTree=$contentStructureTree is_root_node=true() skip_self_node=true() current_node_id=$module_result.node_id}
        </ul>
    {/cache-block}

{/let}

