{let parentNode     = $contentStructureTree.parent_node
     children       = $contentStructureTree.children
     haveChildren   = count($contentStructureTree.children)|gt(0)
     showToolTips   = ezini( 'TreeMenu', 'ToolTips', 'contentstructuremenu.ini' )
     toolTip        = "" }

    <li id="n{$:parentNode.node.node_id}">

        {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
            {section show=$:haveChildren}
               <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('contentstructuremenu/show_content_structure')}"
                  onclick="ezpopmnu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>
            {section-else}
                <span class="openclose"></span>
            {/section}

        {* Icon *}
            <a class="nodeicon" href="#" onclick="ezpopmnu_showTopLevel( 'ContextMenu','{$:parentNode.node.node_id}' , '{$:parentNode.object.id}' , '{$:parentNode.object.name|shorten(18)}'); return false;">{$:parentNode.object.class_identifier|class_icon( small, "Show 'Edit' menu" )}</a>
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
                <a class="nodetext" href="#" onclick="this.href='javascript:ezcst_onItemClicked( {$:parentNode.node.node_id}, \'{$:defaultItemClickAction}\' )'" title="{$:toolTip}">{$:parentNode.object.name|wash}</a>
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