{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         toolTip        = ""
         visibility     = 'Visible' }

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' ) }

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

        <li id="n{$:parentNode.node.node_id}"{section show=$:last_item} class="lastli"{/section}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {section show=$:haveChildren}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/admin/contentstructuremenu')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {section-else}
                    <span class="openclose"><img src="../../images/1x1.gif" width="16" height="16" alt="" /></span> {* This image should be inserted by javascript, and removed from here *}
                {/section}

            {* Icon *}
            {section show=eq( $#ui_context, 'browse' )}
                <a class="nodeicon" href="#">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize )}</a>
            {section-else}
                <a class="nodeicon" href="#" onclick="ezpopmenu_showTopLevel( event, 'ContextMenu', ez_createAArray( new Array( '%nodeID%', {$:parentNode.node.node_id}, '%objectID%', {$:parentNode.object.id} ) ) , '{$:parentNode.object.name|shorten(18)|wash(javascript)}', {$:parentNode.node.node_id} ); return false;">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize, "[%classname] Click on the icon to get a context sensitive menu."|i18n( 'design/admin/contentstructuremenu',, hash( '%classname', $:parentNode.object.class_name ) ) )}</a>
            {/section}
            {* Label *}
                {* Tooltip *}
                {section show=$:showToolTips|eq('enabled')}
                    {section show=$:parentNode.node.is_invisible}
                        {set visibility = 'Hidden by superior'}
                    {/section}
                    {section show=$:parentNode.node.is_hidden}
                        {set visibility = 'Hidden'}
                    {/section}
                    {set toolTip = 'Node ID: %node_id Visibility: %visibility' |
                                    i18n("contentstructuremenu/show_content_structure", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                                '%visibility'   , $:visibility ) ) }
                {section-else}
                    {set toolTip = ''}
                {/section}

                {* Text *}
                {section show=$:csm_menu_item_click_action|eq('')}
                    {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                        <a class="nodetext" href="{$:defaultItemClickAction}"  title="{$:toolTip}">
                    {/let}
                {section-else}
                        <a class="nodetext" href="{$:csm_menu_item_click_action}/{$:parentNode.node.node_id}" title="{$:toolTip}">
                {/section}
        {section show=$:parentNode.node.is_hidden}
                <span class="node-name-hidden">{$:parentNode.object.name|wash}</span>
        {section-else}
            {section show=$:parentNode.node.is_invisible}
                <span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>
            {section-else}
                <span class="node-name-normal">{$:parentNode.object.name|wash}</span>
            {/section}
        {/section}
                {section show=$:parentNode.node.is_hidden}
                <span class="node-hidden">(Hidden)</span>
                {section-else}
                    {section show=$:parentNode.node.is_invisible}
                    <span class="node-hiddenbyparent">(Hidden by parent)</span>
                    {/section}
                {/section}
                </a>


            {* Show children *}
                {section show=$:haveChildren}
                    <ul>
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$:child csm_menu_item_click_action=$:csm_menu_item_click_action last_item=eq( $child.number, $:numChildren )}
                        {/section}
                    </ul>
                {/section}
        </li>
        {/default}
    {/let}
{/section}