{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         toolTip        = ""
         visibility     = 'Visible'
         isRootNode     = false() }

        {default last_item      = false() }

            {if is_set($is_root_node)}
                {set isRootNode=$is_root_node}
            {/if}
            {if $skip_self_node|not()}

                <li id="n{$:parentNode.node.node_id}"
                {if eq($chapter_level,1)}
                    {if eq($unfold_node,$parentNode.node.node_id)}
                        class="topchapter-selected"
                        {else}
                        class="topchapter"
                    {/if}

                    {else}
                    {if and($:last_item, eq($parentNode.node.node_id, $current_node_id))}
                        class="lastli currentnode"
                        {else}
                        {if $:last_item}
                            class="lastli"
                        {/if}
                        {if eq($parentNode.node.node_id, $current_node_id)}
                            class="currentnode"
                        {/if}
                {/if}{/if}>
                {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {if or($:haveChildren, $:isRootNode)}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/standard/simplified_treemenu')}"></a>
                {else}
                    <span class="openclose"></span>
                {/if}

                {* Icon *}
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
                                    i18n("simplified_treemenu/show_simplified_menu", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                             '%visibility'   , $:visibility ) ) }
                {else}
                    {set toolTip = ''}
                {/if}

                {* Text *}
                {* Do not indent this line; otherwise links will contain empty space at the end! *}
                {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}<a class="nodetext" href="{$:defaultItemClickAction}" title="{$:toolTip}">{/let}{if $:parentNode.node.is_hidden}<span class="node-name-hidden">{$:parentNode.object.name|wash}</span>{else}{if $:parentNode.node.is_invisible}<span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>{else}<span class="node-name-normal">{$:parentNode.object.name|wash}</span>{/if}{/if}{if $:parentNode.node.is_hidden}<span class="node-hidden">(Hidden)</span></a>{else}{if $:parentNode.node.is_invisible}<span class="node-hiddenbyparent">(Hidden by parent)</span></a>{else}</a>{/if}{/if}

            {/if}

                {* Show children *}
                {set chapter_level=sum($chapter_level,1)}
                {section show=$:haveChildren}
<ul>
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:simplified_treemenu/show_simplified_menu.tpl" contentStructureTree=$:child last_item=eq( $child.number, $:numChildren ) skip_self_node=false() current_node_id=$current_node_id chapter_level=$chapter_level unfold_node=$unfold_node}
                        {/section}
</ul>
                {/section}
            {if $skip_self_node|not()}
                </li>
            {/if}

        {/default}
    {/let}
{/section}
