<script language="JavaScript" src={"javascript/lib/ezjslibie50support.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/lib/ezjslibcookiesupport.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/lib/ezjslibdomsupport.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/contentstructuremenu/contentstructuremenu.js"|ezdesign}></script>

{let rootNodeID             = ezini( 'TreeMenu', 'RootNodeID'       , 'contentstructuremenu.ini' )
     classFilter            = ezini( 'TreeMenu', 'ShowClasses'      , 'contentstructuremenu.ini' )                            
     maxDepth               = ezini( 'TreeMenu', 'MaxDepth'         , 'contentstructuremenu.ini' ) 
     maxNodes               = ezini( 'TreeMenu', 'MaxNodes'         , 'contentstructuremenu.ini' ) 
     sortBy                 = ezini( 'TreeMenu', 'SortBy'           , 'contentstructuremenu.ini' )
     fetchHidden            = true
     itemClickAction        = ezini( 'TreeMenu', 'ItemClickAction'  , 'contentstructuremenu.ini' )
     menuWidth              = ezini( 'TreeMenu', 'MenuWidth'        , 'contentstructuremenu.ini' )
     menuHeight             = ezini( 'TreeMenu', 'MenuHeight'       , 'contentstructuremenu.ini' )
     contentStructureTree   = false
     menuID                 = "content_tree_menu" }

    {section show=$custom_root_node_id|is_set()}
        {set rootNodeID=$custom_root_node_id}
    {/section}

    {section show=eq( $ui_context, 'browse' )}
        {set itemClickAction='/content/browse/'}
    {/section}    

    {section show=eq( $itemClickAction, '' )|not()}
        {set itemClickAction = $:itemClickAction|ezurl(no)}
    {/section}
    
    {cache-block keys=array($rootNodeID)}     
        {* Fetch content structure. *}
        {set contentStructureTree = content_structure_tree( $:rootNodeID, 
                                                            $:classFilter,
                                                            $:maxDepth,
                                                            $:maxNodes,
                                                            $:sortBy,
                                                            $:fetchHidden ) }
    
        {* Show menu tree. All container nodes are unfolded. *}
        <ul id="{$:menuID}">
            {include uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$contentStructureTree}                                                
        </ul>

    {/cache-block}        
    
    <script language="JavaScript"><!--
        
        {* get path to current node which consists of nodes ids *}
        var nodesList = new Array();
        
        {section var=path loop=$module_result.path}
            nodesList.push( "n{$:path.node_id}" );
        {/section}
        
        ezcst_initializeMenuState( nodesList, "{$:menuID}",  "{$:itemClickAction}" );            
    // -->
    </script>
{/let}  

