{* Media folder admin view template *}

{default with_children=true()
         is_editable=true()
         is_standalone=true()}
{let page_limit=16
     list_count=and( $with_children, fetch( content, list_count, hash( parent_node_id, $node.node_id ) ) )}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}


<div class="objectheader">
    <h2>{$node_name|wash} [{'Folder'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}</h2>
</div>

<div class="object">
    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />

    <p>{attribute_view_gui attribute=$node.object.data_map.description}</p>

    <div class="buttonblock">
    {section show=and($is_editable,$content_object.can_edit)}
        <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
    {/section}
    <input class="button" type="submit" name="ActionAddToBookmarks" value="{'Bookmark'|i18n('design/standard/node/view')}" />

    </div>
</div>

{let name=Object related_objects=$content_version.related_contentobject_array}
{section name=ContentObject  loop=$Object:related_objects show=$Object:related_objects  sequence=array( bglight, bgdark )}
    <div class="block">
        {content_view_gui view=text_linked content_object=$Object:ContentObject:item}
    </div>
{/section}
{/let}

{section show=$is_standalone}
    {section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
        <div class="block">
            <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|wash}" />
        </div>
    {/section}
{/section}

<div class="buttonblock">
    {section show=$content_object.can_create}
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
        <select name="ClassID">
        {section name=Classes loop=$content_object.can_create_class_list}
            <option value="{$:item.id}">{$:item.name|wash}</option>
        {/section}
        </select>
        <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" />
    {/section}
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
    <input type="hidden" name="ViewMode" value="full" />
</div>

{section show=$with_children}
    {let name=Child
        children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             sort_by, $node.sort_array,     
                                             limit, $page_limit, 
                                             offset, $view_parameters.offset ) )
        can_remove=false() 
        can_edit=false() 
        can_create=false()}

    {section show=$:children}

        {section loop=$:children}
            {section show=$:item.object.can_remove}
                {set can_remove=true()}
            {/section}
            {section show=$:item.object.can_edit}
                {set can_edit=true()}
            {/section}
            {section show=$:item.object.can_create}
                {set can_create=true()}
            {/section}
        {/section}

        {set can_copy=$content_object.can_create}



        <table cellspacing="8" cellpadding="0" border="0">
        <tr>
        {section loop=$:children sequence=array( bglight, bgdark )}

                <td valign="top" width="25%">
                {section show=$:can_remove}
                        {section show=$:item.object.can_remove}
                            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
                        {/section}
                {/section}
                {section show=$:can_edit}
                        {section show=$:item.object.can_edit}
                            <a href={concat( "content/edit/", $Child:item.contentobject_id )|ezurl}><img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/standard/node/view')}" /></a>
                        {/section}
                {/section}
                {section show=eq( $node.sort_array[0][0], 'priority' )}
                        <input type="text" name="Priority[]" size="2" value="{$Child:item.priority}">
                        <input type="hidden" name="PriorityID[]" value="{$Child:item.node_id}">
                {/section}
                    {node_view_gui view=thumbnail content_node=$:item}
                </td>
                {delimiter modulo=4}
                </tr><tr>
                {/delimiter}
        {/section}
        </tr>
        </table>

        {section show=$:can_remove}
            {section show=fetch(content, list, hash(
                                               parent_node_id, $node.node_id,
                                               sort_by, $node.sort_array,
                                               limit, $page_limit,
                                               offset, $view_parameters.offset ) )}
                <input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/node/view')}" />
            {/section}
        {/section}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
                 <input class="button" align="right" type="submit" name="UpdatePriorityButton" value="{'Update'|i18n('design/standard/node/view')}" />
            {/section}
        {/section}
    {/section}
    {/let}

    {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$list_count
             view_parameters=$view_parameters
             item_limit=$page_limit}


{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
{/let}
{/default}
