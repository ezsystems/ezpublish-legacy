<script language="JavaScript" src={"javascript/contentstructuremenu.js"|ezdesign}></script>

{let rootNodeID             = ezini( 'TreeMenu', 'RootNodeID'       , 'contentstructuremenu.ini' )
     classFilter            = ezini( 'TreeMenu', 'ShowClasses'      , 'contentstructuremenu.ini' )                            
     maxDepth               = ezini( 'TreeMenu', 'MaxDepth'         , 'contentstructuremenu.ini' ) 
     maxNodes               = ezini( 'TreeMenu', 'MaxNodes'         , 'contentstructuremenu.ini' ) 
     sortBy                 = ezini( 'TreeMenu', 'SortBy'           , 'contentstructuremenu.ini' )
     fetchHidden            = true
     menuWidth              = ezini( 'TreeMenu', 'MenuWidth'        , 'contentstructuremenu.ini' )
     menuHeight             = ezini( 'TreeMenu', 'MenuHeight'       , 'contentstructuremenu.ini' )
     contentStructureTree   = false
     menuID                 = "content_tree_menu" }
     
     
    {cache-block}     
        {* Fetch content structure. *}
        {set contentStructureTree = content_structure_tree( $:rootNodeID, 
                                                            $:classFilter,
                                                            $:maxDepth,
                                                            $:maxNodes,
                                                            $:sortBy,
                                                            $:fetchHidden ) }
    
        {* Show menu tree. All container nodes are unfolded. *}
        <ul id="{$:menuID}">
            {include uri="design:menu/show_content_structure.tpl" contentStructureTree=$contentStructureTree}                                                
        </ul>

    {/cache-block}        
    
    <script language="JavaScript"><!--
        
        {* get path to current node which consists of nodes ids *}
        var nodesList = new Array();
        
        {section var=path loop=$module_result.path}
            nodesList.push( "n{$:path.node_id}" );
        {/section}
        
        nodesList.pop(); // remove current node;
        
        ezcst_initializeMenuState( nodesList, "{$:menuID}" );            
    // -->
    </script>
{/let}  

