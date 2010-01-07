{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         canCreateClasses = $contentStructureTree.parent_node.classes_js_array
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
         toolTip        = ""
         visibility     = 'Visible'|i18n('design/admin/contentstructuremenu')
         isRootNode     = false() }

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' )
                 last_item      = false() }

        {if is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/if}

        {if is_set($is_root_node)}
            {set isRootNode=$is_root_node}
        {/if}

        <li id="n{$:parentNode.node.node_id}"{if $:last_item} class="lastli"{/if}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {if or($:haveChildren, $:isRootNode)}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/admin/contentstructuremenu')|wash}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {else}
                    <span class="openclose"></span>
                {/if}

            {* Icon *}
            {if eq( $#ui_context, 'browse' )}
                <a class="nodeicon" href="#">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize )}</a>
            {else}
                <a class="nodeicon" href="#" onclick="ezpopmenu_showTopLevel( event, 'ContextMenu', ez_createAArray( new Array( '%nodeID%', {$:parentNode.node.node_id}, '%objectID%', {$:parentNode.object.id}, '%languages%', {$:parentNode.object.language_js_array}, '%classList%', {$:canCreateClasses} ) ) , '{$:parentNode.object.name|shorten(18)|wash(javascript)}', {$:parentNode.node.node_id}, {cond( eq( $:canCreateClasses, "''" ), "'menu-create-here'", '-1' )} ); return false;">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize, "[%classname] Click on the icon to display a context-sensitive menu."|i18n( 'design/admin/contentstructuremenu',, hash( '%classname', $:parentNode.object.class_name ) ) )}</a>
            {/if}
            {* Label *}
                {* Tooltip *}
                {if $:showToolTips|eq('enabled')}
                    {if $:parentNode.node.is_invisible}
                        {set visibility = 'Hidden by superior'|i18n('design/admin/contentstructuremenu')}
                    {/if}
                    {if $:parentNode.node.is_hidden}
                        {set visibility = 'Hidden'|i18n('design/admin/contentstructuremenu')}
                    {/if}
                    {set toolTip = 'Node ID: %node_id Visibility: %visibility' |
                                    i18n("contentstructuremenu/show_content_structure", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                                '%visibility'   , $:visibility ) ) }
                {else}
                    {set toolTip = ''}
                {/if}

                {* Text *}
                {if or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
                    {if $:csm_menu_item_click_action|eq('')}
                        {if eq( $:translation, 'enabled' )}
                            {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                                <a class="image-text" href="{$:defaultItemClickAction}" title="{$:toolTip|wash}">
                            {/let}
                        {else}
                            {let defaultItemClickAction = concat('content/view/full/',$:parentNode.node.node_id)|ezurl(no)}
                                <a class="image-text" href="{$:defaultItemClickAction}" title="{$:toolTip|wash}">
                            {/let}
                        {/if}
                        {* Do not indent this line; otherwise links will contain empty space at the end! *}
                        {else}<a class="image-text" href="{$:csm_menu_item_click_action}/{$:parentNode.node.node_id}" title="{$:toolTip|wash}">{/if}{if $:parentNode.node.is_hidden}<span class="node-name-hidden">{$:parentNode.object.name|wash}</span>{else}{if $:parentNode.node.is_invisible}<span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>{else}<span class="node-name-normal">{$:parentNode.object.name|wash}</span>{/if}{/if}{if $:parentNode.node.is_hidden}<span class="node-hidden">(Hidden)</span></a>{else}{if $:parentNode.node.is_invisible}<span class="node-hiddenbyparent">(Hidden by parent)</span></a>{else}</a>{/if}
                    {/if}
                {else}
                    {if $:parentNode.node.is_hidden}
                        <span class="node-name-hidden">{$:parentNode.object.name|wash}</span>
                    {else}
                        {if $:parentNode.node.is_invisible}
                            <span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>
                        {else}
                            <span class="node-name-normal">{$:parentNode.object.name|wash}</span>
                        {/if}
                    {/if}
                    {if $:parentNode.node.is_hidden}
                        <span class="node-hidden">(Hidden)</span>
                    {else}
                        {if $:parentNode.node.is_invisible}
                            <span class="node-hiddenbyparent">(Hidden by parent)</span>
                        {/if}
                    {/if}
                {/if}

                {* Show children *}
                {section show=$:haveChildren}
                    <ul>
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$:child csm_menu_item_click_action=$:csm_menu_item_click_action last_item=eq( $child.number, $:numChildren ) ui_context=$ui_context}
                        {/section}
                    </ul>
                {/section}
        </li>
        {/default}
    {/let}
{/section}