{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         haveChildren   = count($contentStructureTree.children)|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize'   , 'contentstructuremenu.ini' )
         toolTip        = "" }

         <li id="n{$:parentNode.node.node_id}">

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {section show=$:haveChildren}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('contentstructuremenu/show_content_structure')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {section-else}
                    <span class="openclose"></span>
                {/section}

            {* Icon *}
                <a class="nodeicon" href="#" onclick="ezpopmenu_showTopLevel( event, 'ContextMenu', ez_createAArray( new Array( '%nodeID%', {$:parentNode.node.node_id}, '%objectID%', {$:parentNode.object.id} ) ) , '{$:parentNode.object.name|shorten(18)|wash(javascript)}', {$:parentNode.node.node_id} ); return false;">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize, "Show 'Edit' menu" )}</a>
            {* Label *}
                {* Tooltip *}
                {section show=$:showToolTips|eq('enabled')}
                    {set toolTip = 'Node ID: %node_id Created: %created Children num: %children_num' |
                                    i18n("contentstructuremenu/show_content_structure", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                                '%created'      , $:parentNode.object.published|l10n(shortdatetime),
                                                                                                '%children_num' , $:parentNode.node.children_count ) ) }
                {section-else}
                    {set toolTip = ''}
                {/section}

                {* Text *}
                {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                    <a class="nodetext" href="{$:defaultItemClickAction}" onclick="this.href='javascript:ezcst_onItemClicked( {$:parentNode.node.node_id}, \'{$:defaultItemClickAction}\' )'" title="{$:toolTip}">
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
                {/let}

            {* Show children *}
                {section show=$:haveChildren}
                    <ul>
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$:child}
                        {/section}
                    </ul>
                {/section}
        </li>
    {/let}
{/section}