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

            {section show=is_set($is_root_node)}
                {set isRootNode=$is_root_node}
            {/section}
            {section show=$skip_self_node|not()}

                <li id="n{$:parentNode.node.node_id}"
                {section show=eq($chapter_level,1)}
                    {section show=eq($unfold_node,$parentNode.node.node_id)}
                        class="topchapter-selected"
                        {section-else}
                        class="topchapter"
                    {/section}

                    {section-else}
                    {section show=and($:last_item, eq($parentNode.node.node_id, $current_node_id))}
                        class="lastli currentnode"
                        {section-else}
                        {section show=$:last_item}
                            class="lastli"
                        {/section}
                        {section show=eq($parentNode.node.node_id, $current_node_id)}
                            class="currentnode"
                        {/section}
                {/section}{/section}>
                {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {section show=or($:haveChildren, $:isRootNode)}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/standard/simplified_treemenu')}"></a>
                {section-else}
                    <span class="openclose"></span>
                {/section}

                {* Icon *}
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
                                    i18n("simplified_treemenu/show_simplified_menu", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                             '%visibility'   , $:visibility ) ) }
                {section-else}
                    {set toolTip = ''}
                {/section}

                {* Text *}
                {* Do not indent this line; otherwise links will contain empty space at the end! *}
                {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}<a class="nodetext" href="{$:defaultItemClickAction}" title="{$:toolTip}">{/let}{section show=$:parentNode.node.is_hidden}<span class="node-name-hidden">{$:parentNode.object.name|wash}</span>{section-else}{section show=$:parentNode.node.is_invisible}<span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>{section-else}<span class="node-name-normal">{$:parentNode.object.name|wash}</span>{/section}{/section}{section show=$:parentNode.node.is_hidden}<span class="node-hidden">(Hidden)</span></a>{section-else}{section show=$:parentNode.node.is_invisible}<span class="node-hiddenbyparent">(Hidden by parent)</span></a>{section-else}</a>{/section}{/section}

            {/section}

                {* Show children *}
                {set chapter_level=sum($chapter_level,1)}
                {section show=$:haveChildren}
<ul>
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:simplified_treemenu/show_simplified_menu.tpl" contentStructureTree=$:child last_item=eq( $child.number, $:numChildren ) skip_self_node=false() current_node_id=$current_node_id chapter_level=$chapter_level unfold_node=$unfold_node}
                        {/section}
</ul>
                {/section}
            {section show=$skip_self_node|not()}
                </li>
            {/section}

        {/default}
    {/let}
{/section}
