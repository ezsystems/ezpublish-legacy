{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         toolTip        = ""
         visibility     = 'Visible'
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
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/standard/contentstructuremenu')}"
                      onclick="ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {else}
                    <span class="openclose"></span>
                {/if}

            {* Icon *}
            {if eq( $#ui_context, 'browse' )}
                <a class="nodeicon" href={$:parentNode.node.path_identification_string|ezurl}>{$:parentNode.object.class_identifier|class_icon( $:classIconsSize )}</a>
            {else}
                <a class="nodeicon" href={$:parentNode.node.path_identification_string|ezurl}>{$:parentNode.object.class_identifier|class_icon( $:classIconsSize, "[%classname] Click on the icon to display a context-sensitive menu."|i18n( 'design/standard/contentstructuremenu',, hash( '%classname', $:parentNode.object.class_name ) ) )}</a>
            {/if}
            {* Label *}
                {* Tooltip *}
                {if $:showToolTips|eq('enabled')}
                    {if $:parentNode.node.is_invisible}
                        {set visibility = 'Hidden by superior'}
                    {/if}
                    {if $:parentNode.node.is_hidden}
                        {set visibility = 'Hidden'}
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
                        {* Do not indent this line; otherwise links will contain empty space at the end! *}
                        {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}<a class="nodetext" href="{$:defaultItemClickAction}" title="{$:toolTip}">{/let}{else}<a class="nodetext" href="{$:csm_menu_item_click_action}/{$:parentNode.node.node_id}" title="{$:toolTip}">{/if}{if $:parentNode.node.is_hidden}<span class="node-name-hidden">{$:parentNode.object.name|wash}</span>{else}{if $:parentNode.node.is_invisible}<span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>{else}<span class="node-name-normal">{$:parentNode.object.name|wash}</span>{/if}{/if}{if $:parentNode.node.is_hidden}<span class="node-hidden">(Hidden)</span></a>{else}{if $:parentNode.node.is_invisible}<span class="node-hiddenbyparent">(Hidden by parent)</span></a>{else}</a>{/if}
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