<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibie50support.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibcookiesupport.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibdomsupport.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibimagepreloader.js"|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={"javascript/contentstructuremenu/contentstructuremenu.js"|ezdesign}></script>


{let rootNodeID             = ezini( 'TreeMenu', 'RootNodeID'       , 'contentstructuremenu.ini' )
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
     isDepthUnlimited       = eq($:maxDepth, 0) }

    {* check size of icons *}
    {section show=is_set($:class_icons_size)}
        {set classIconsSize=$:class_icons_size}
    {/section}

    {* load icons if preloadClassIcons is enabled *}
    {section show=eq( $:preloadClassIcons, "enabled" )}
        {let iconsRepository    = ezini( 'IconSettings', 'Repository', 'icon.ini' )
             iconsTheme         = ezini( 'IconSettings', 'Theme'     , 'icon.ini' )
             classMap           = ezini( 'ClassIcons'  , 'ClassMap'  , 'icon.ini' )
             defaultIcon        = ezini( 'ClassIcons'  , 'Default'   , 'icon.ini' )
             iconsSizesList     = ezini( 'IconSettings', 'Sizes'     , 'icon.ini' )
             iconsSize          = $:iconsSizesList.$:classIconsSize }

            <script language="JavaScript" type="text/javascript"><!--

                var iconsList       = new Array();
                var wwwDirPrefix    = "{ezsys('wwwdir')}";
                var iconPath        = "";

                // oridinary icons.
                {section var=icon loop=$:classMap}
                    iconPath = wwwDirPrefix + "/" + "{$:iconsRepository}" + "/" + "{$:iconsTheme}" + "/" + "{$:iconsSize}" + "/" + "{$:icon}";
                    iconsList.push( iconPath );
                {/section}

                // default icon.
                iconPath = wwwDirPrefix + "/" + "{$:iconsRepository}" + "/" + "{$:iconsTheme}" + "/" + "{$:iconsSize}" + "/" + "{$:defaultIcon}";
                iconsList.push( iconPath );

                // load them all!!
                ezjslib_preloadImageList( iconsList );

            // -->
            </script>
        {/let}
    {/section}

    {* check custom_root_node *}
    {section show=is_set( $custom_root_node_id )}
        {set rootNodeID=$custom_root_node_id}
    {/section}

    {* check custom action when clicking on menu item *}
    {section show=and( is_set( $csm_menu_item_click_action ), eq( $itemClickAction, '' ) )}
        {set itemClickAction=$csm_menu_item_click_action}
    {/section}

    {* if menu action is set translate it to url *}
    {section show=eq( $itemClickAction, '' )|not()}
        {set itemClickAction = $:itemClickAction|ezurl(no)}
    {/section}

    {* create menu *}
    {default current_user=fetch('user','current_user')}
    {cache-block keys=array($:itemClickAction, $:rootNodeID,$current_user.role_id_list|implode( ',' )) subtree_expire="$:parentNode.object.name/"}
        {* Fetch content structure. *}
        {set contentStructureTree = content_structure_tree( $:rootNodeID,
                                                            $:classFilter,
                                                            $:maxDepth,
                                                            $:maxNodes,
                                                            $:sortBy,
                                                            $:fetchHidden ) }

        {* Show menu tree. All container nodes are unfolded. *}
            <ul id="{$:menuID}">
                {include uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$contentStructureTree class_icons_size=$:classIconsSize csm_menu_item_click_action=$:itemClickAction}
            </ul>
    {/cache-block}
    {/default}
    {* initialize menu *}
    <script language="JavaScript" type="text/javascript"><!--

        {* get path to current node which consists of nodes ids *}
        var nodesList = new Array();

        {section var=path loop=$module_result.path}
            {section show=and(is_set($:path.node_id), or($:isDepthUnlimited, $:maxDepth|gt(0)))}
                nodesList.push( "n{$:path.node_id}" );
                {set maxDepth = dec($:maxDepth)}
            {/section}
        {/section}


        ezcst_setFoldUnfoldIcons( {"images/content_tree-open.gif"|ezdesign}, {"images/content_tree-close.gif"|ezdesign} );
        ezcst_initializeMenuState( nodesList, "{$:menuID}", "{$:autoopenCurrentNode}" );
    // -->
    </script>

{/let}

