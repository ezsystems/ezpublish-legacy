<form method="post" action={"content/action"|ezurl}>
    {let name=Child
        children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                             sort_by, $node.sort_array,
                                             limit, $page_limit,
                                             offset, $view_parameters.offset ) )
        can_remove=false()
        can_edit=false()
        can_create=false()
        can_copy=false()}

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



        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            {section show=$:can_remove}
                <th width="1">
                    &nbsp;
                </th>
            {/section}
            <th>
                {"Name"|i18n("design/standard/node/view")}
            </th>
            <th>
                {"Class"|i18n("design/standard/node/view")}
            </th>
            <th>
                {"Section"|i18n("design/standard/node/view")}
            </th>
            {section show=eq( $node.sort_array[0][0], 'priority' )}
                <th>
                    {"Priority"|i18n( "design/standard/node/view" )}
                </th>
            {/section}
            {section show=$:can_edit}
                <th width="1">
                    {"Edit"|i18n("design/standard/node/view")}
                </th>
            {/section}
            {section show=$:can_copy}
                <th width="1">
                    {"Copy"|i18n("design/standard/node/view")}
                </th>
            {/section}
        </tr>
        {section loop=$:children sequence=array( bglight, bgdark )}
            <tr class="{$Child:sequence}">
                {section show=$:can_remove}
                    <td align="right" width="1">
                        {section show=$:item.object.can_remove}
                            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
                        {/section}
                    </td>
                {/section}
                <td>
                    <a href={$:item.url_alias|ezurl}>{node_view_gui view=line content_node=$:item}</a>
                    {* {node_view_gui view=line content_node=$:item} *}
                </td>
                <td>
                    {$Child:item.object.class_name|wash}
                </td>
                <td>
                    {$Child:item.object.section_id}
                </td>
                {section show=eq( $node.sort_array[0][0], 'priority' )}
                    <td width="40" align="left">
                        <input type="text" name="Priority[]" size="2" value="{$Child:item.priority}">
                        <input type="hidden" name="PriorityID[]" value="{$Child:item.node_id}">
                    </td>
                {/section}

                {section show=$:can_edit}
                    <td width="1">
                        {section show=$:item.object.can_edit}
                            <a href={concat( "content/edit/", $Child:item.contentobject_id )|ezurl}><img src={"edit.png"|ezimage} alt="Edit" /></a>
                        {/section}
                    </td>
                {/section}
                {section show=$:can_copy}
                    <td>
                        <a href={concat( "content/copy/", $Child:item.contentobject_id )|ezurl}><img src={"copy.gif"|ezimage} alt="{'Copy'|i18n( 'design/standard/node/view' )}" /></a>
                    </td>
                {/section}

            </tr>
        {/section}
        </table>

{*           {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$list_count
             view_parameters=$view_parameters
             item_limit=$page_limit}
*}

<div class="controlbar">
        {section show=$:can_edit}
        {/section}
        {section show=$:can_copy}
        {/section}
        {section show=$:can_remove}
            {section show=fetch(content, list, hash(
                                               parent_node_id, $node.node_id,
                                               sort_by, $node.sort_array,
                                               limit, $page_limit,
                                               offset, $view_parameters.offset ) )}
                <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/node/view')}" />
            {/section}
        {/section}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
                 <input class="button" align="right" type="submit" name="UpdatePriorityButton" value="{'Update'|i18n('design/standard/node/view')}" />
            {/section}
        {/section}
    {section-else}
    <div class="controlbar">
    {/section}
    {/let}

{* The "Create new here" thingy: *}

{section show=$node.object.can_create}
<div class="createblock">
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<select name="ClassID">
{section name=Classes loop=$node.object.can_create_class_list}
<option value="{$:item.id}">{$:item.name|wash}</option>
{/section}
</select>
<input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" nam="ViewMode" value="full" />
</div>
</form>
</div>
