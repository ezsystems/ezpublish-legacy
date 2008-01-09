{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         numChildren    = count($contentStructureTree.children)
         canCreateClasses = $contentStructureTree.parent_node.classes_js_array
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
         toolTip        = ""
         visibility     = 'Visible'
         isRootNode     = false() }

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' )
                 last_item      = false() }

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

        {section show=is_set($is_root_node)}
            {set isRootNode=$is_root_node}
        {/section}

        <li id="n{$:parentNode.node.node_id}"{section show=$:last_item} class="lastli"{/section}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {section show=or($:haveChildren, $:isRootNode)}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/admin/contentstructuremenu')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {section-else}
                    <span class="openclose"></span>
                {/section}

            {* Icon *}
            {section show=eq( $#ui_context, 'browse' )}
                <a class="nodeicon" href="#">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize )}</a>
            {section-else}
                <a class="nodeicon" href="#" onclick="ezpopmenu_showTopLevel( event, 'ContextMenu', ez_createAArray( new Array( '%nodeID%', {$:parentNode.node.node_id}, '%objectID%', {$:parentNode.object.id}, '%languages%', {$:parentNode.object.language_js_array}, '%classList%', {$:canCreateClasses} ) ) , '{$:parentNode.object.name|shorten(18)|wash(javascript)}', {$:parentNode.node.node_id}, {cond( eq( $:canCreateClasses, "''" ), "'menu-create-here'", '-1' )} ); return false;">{$:parentNode.object.class_identifier|class_icon( $:classIconsSize, "[%classname] Click on the icon to display a context-sensitive menu."|i18n( 'design/admin/contentstructuremenu',, hash( '%classname', $:parentNode.object.class_name ) ) )}</a>
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
                {section show=or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
                    {section show=$:csm_menu_item_click_action|eq('')}
                        {section show=eq( $:translation, 'enabled' )}
                            {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                                <a class="nodetext" href="{$:defaultItemClickAction}" title="{$:toolTip}">
                            {/let}
                        {section-else}
                            {let defaultItemClickAction = concat('content/view/full/',$:parentNode.node.node_id)|ezurl(no)}
                                <a class="nodetext" href="{$:defaultItemClickAction}" title="{$:toolTip}">
                            {/let}
                        {/section}
                        {* Do not indent this line; otherwise links will contain empty space at the end! *}
                        {section-else}<a class="nodetext" href="{$:csm_menu_item_click_action}/{$:parentNode.node.node_id}" title="{$:toolTip}">{/section}{section show=$:parentNode.node.is_hidden}<span class="node-name-hidden">{$:parentNode.object.name|wash}</span>{section-else}{section show=$:parentNode.node.is_invisible}<span class="node-name-hiddenbyparent">{$:parentNode.object.name|wash}</span>{section-else}<span class="node-name-normal">{$:parentNode.object.name|wash}</span>{/section}{/section}{section show=$:parentNode.node.is_hidden}<span class="node-hidden">(Hidden)</span></a>{section-else}{section show=$:parentNode.node.is_invisible}<span class="node-hiddenbyparent">(Hidden by parent)</span></a>{section-else}</a>{/section}
                    {/section}
                {section-else}
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
                {/section}

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