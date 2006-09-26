{let rootNodeID             = $nodeID
     classFilter            = ezini( 'TreeMenu', 'ShowClasses'      , 'contentstructuremenu.ini' )
     maxDepth               = ezini( 'TreeMenu', 'MaxDepth'         , 'contentstructuremenu.ini' )
     maxNodes               = ezini( 'TreeMenu', 'MaxNodes'         , 'contentstructuremenu.ini' )
     sortBy                 = ezini( 'TreeMenu', 'SortBy'           , 'contentstructuremenu.ini' )
     fetchHidden            = ezini( 'SiteAccessSettings', 'ShowHiddenNodes', 'site.ini'         )
     itemClickAction        = ezini( 'TreeMenu', 'ItemClickAction'  , 'contentstructuremenu.ini' )
     classIconsSize         = ezini( 'TreeMenu', 'ClassIconsSize'   , 'contentstructuremenu.ini' )
     preloadClassIcons      = ezini( 'TreeMenu', 'PreloadClassIcons', 'contentstructuremenu.ini' )
     autoopenCurrentNode    = ezini( 'TreeMenu', 'AutoopenCurrentNode', 'contentstructuremenu.ini' )
     contentStructureTree   = false()
     menuID                 = "content_tree_menu"
     isDepthUnlimited       = eq($:maxDepth, 0)
     rootNode               = false
     nodesList              = array($nodeID) }

    {* check custom action when clicking on menu item *}
    {section show=and( is_set( $csm_menu_item_click_action ), eq( $itemClickAction, '' ) )}
        {set itemClickAction=$csm_menu_item_click_action}
    {/section}

    {* if menu action is set translate it to url *}
    {section show=eq( $itemClickAction, '' )|not()}
        {set itemClickAction = $:itemClickAction|ezurl(no)}
    {/section}

{set contentStructureTree = content_structure_tree( $:rootNodeID,
                                                            $:classFilter,
                                                            $:maxDepth,
                                                            $:maxNodes,
                                                            $:sortBy,
                                                            $:fetchHidden ) }



{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         toolTip        = ""
         visibility     = 'Visible'
         isRootNode     = false()
         thisNodeID     = $contentStructureTree.parent_node.node.node_id}

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' )
                 last_item      = false() }

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

        {section show=is_set($is_root_node)}
            {set isRootNode=$is_root_node}
        {/section}


                {* Show children *}
                {section show=$:haveChildren}
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:contentstructuremenu/show_dynamic_content_structure.tpl" contentStructureTree=$:child csm_menu_item_click_action=$:itemClickAction last_item=eq( $child.number, $:numChildren ) ui_context=$ui_context nodesList=$nodesList lastNodeID=$rootNodeID}
                        {/section}
                {/section}
        {/default}
    {/let}
{/section}

{/let}
