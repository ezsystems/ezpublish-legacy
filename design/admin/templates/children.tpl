<form method="post" action={"content/action"|ezurl}>

{let name=Child
     can_remove=false()
     can_edit=false()
     can_create=false()
     can_copy=false()
     children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          sort_by, $node.sort_array,
                                          limit, $page_limit,
                                          offset, $view_parameters.offset ) )

}

{* If there are children: show the list and a button bar that belongs to it. *}
{section show=$:children}

    {* Copying operation is allowed if the user can create stuff under the current node. *}
    {set can_copy=$node.object.can_create}

    {* Check if the current user is allowed to *}
    {* edit or delete any of the children.     *}
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

<div class="admin-childlist">

    <table class="list" cellspacing="0">
    <tr>
        <th class="remove"> &nbsp; </th>
        <th class="name">{"Name"|i18n("design/standard/node/view")}</th>
        <th class="class">{"Type"|i18n("design/standard/node/view")}</th>
        <th class="section">{"Section"|i18n("design/standard/node/view")}</th>
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{"Priority"|i18n( "design/standard/node/view" )}</th>
        {/section}
        {section show=$:can_edit}
            <th class="edit">{"Edit"|i18n("design/standard/node/view")}</th>
        {/section}
            <th class="copy">{"Copy"|i18n("design/standard/node/view")}</th>
        {/section}
    </tr>


    {section loop=$:children sequence=array( bglight, bgdark )}
        <tr class="{$Child:sequence}">
        <td>
        {section show=$:item.object.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" />
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Child:item.node_id}" disabled="disabled" />
        {/section}
        </td>

        <td>{node_view_gui view=line content_node=$:item}</td>
        <td>{$Child:item.object.class_name|wash}</td>
        <td>{$Child:item.object.section_id}</td>

        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {section show=$Child:item.can_edit}
                <input type="text" name="Priority[]" size="2" value="{$Child:item.priority}" />
                <input type="hidden" name="PriorityID[]" value="{$Child:item.node_id}" />
                {section-else}
                <input type="text" name="Priority[]" size="2" value="{$Child:item.priority}" disabled="disabled" />
            {/section}
            </td>
        {/section}

        {section show=$:can_edit}
            <td>
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
</div>


{*           {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat('/content/view','/full/',$node.node_id)
             item_count=$list_count
             view_parameters=$view_parameters
             item_limit=$page_limit}
*}


<div class="controlbar">

    {* The remove button *}
    {section show=$:can_remove}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/node/view')}" />
    {section-else}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/node/view')}" disabled="disabled" />
    {/section}

    {* The update priorities button *}
    {section show=eq( $node.sort_array[0][0], 'priority' )}
    {section show=$:can_edit}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n('design/standard/node/view')}" />
    {section-else}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n('design/standard/node/view')}" disabled="disabled" />
    {/section}
    {/section}

{section-else}
<div class="controlbar">
<p>This node does not have any children.</p>
{/section}

<br />

{* The "Create new here" thing: *}

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
<input type="hidden" name="ViewMode" value="full" />
</div>
{section-else}
<select name="ClassID" disabled="disabled">
<option value="">Not available</option>
</select>
<input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" disabled="disabled" />
</div>
{/section}
</form>
</div>


{/let}