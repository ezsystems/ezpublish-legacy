{* Person admin view template *}

{default with_children=true()
         is_editable=true()
         is_standalone=true()}
{let page_limit=15
     list_count=and( $with_children, fetch( content, list_count, hash( parent_node_id, $node.node_id ) ) )}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}

<div class="objectheader">
    <h2>{$node_name|wash} [{'Person'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}</h2>
</div>

<div class="object">

    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />

    <h3>{"Job title"|i18n("design/admin/node/view")}</h3>
    <p>{attribute_view_gui attribute=$node.object.data_map.job_title}</p>

    {section show=$node.object.data_map.picture.content}
        <div class="imageright">
        {attribute_view_gui attribute=$node.object.data_map.picture.content.data_map.image image_class=small}
        </div>
    {/section}

    <h3>{"Contact information"|i18n("design/admin/node/view")}</h3>
    <p>{attribute_view_gui attribute=$node.object.data_map.contact_information}</p>

    <h3>{"Comment"|i18n("design/admin/node/view")}</h3>
    <p>{attribute_view_gui attribute=$node.object.data_map.comment}</p>

    <div class="buttonblock">
    <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
    {section show=and($is_editable,$content_object.can_edit)}
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
    {/section}
    <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" />
    {*<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" />*}
    <input class="button" type="submit" name="ActionAddToBookmarks" value="{'Bookmark'|i18n('design/standard/node/view')}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="{'Keep me updated'|i18n('design/standard/node/view')}" />

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
            {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
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
                {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
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

        {section show=eq( $node.sort_array[0][0], 'priority' )}
            {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
                 <input class="button" type="submit"  name="UpdatePriorityButton" value="{'Update'|i18n('design/standard/node/view')}" />
            {/section}
        {/section}
        {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
        {/section}
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
                <input type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/node/view')}" />
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
