{* Folder admin view template *}

{default with_children=true()
         is_editable=true()
         is_standalone=true()}
{let page_limit=15
     list_count=and( $with_children, fetch( content, list_count, hash( parent_node_id, $node.node_id ) ) )
     policies=fetch( 'user', 'user_role', hash( user_id, $node.object.id ) )}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}

<div class="objectheader">
    <h2>{$node_name|wash} [{'User group'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}</h2>
</div>

<div class="object">
    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />

    <p>{attribute_view_gui attribute=$node.object.data_map.description}</p>

    <div class="buttonblock">
    {section show=and($is_editable,$content_object.can_edit)}
        <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
    {/section}
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

{let user_class_group_id=ezini('UserSettings','UserClassGroupID')
     user_class_list_allowed=fetch('content','can_instantiate_classes',hash(parent_node,$node))
     user_class_list=fetch('content','can_instantiate_class_list',hash(group_id,$user_class_group_id,parent_node,$node))}
{section show=$user_class_list_allowed}
<div class="buttonblock">
         <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <select name="ClassID" class="create">
	      {section name=Classes loop=$user_class_list}
	      <option value="{$:item.id}">{$:item.name|wash}</option>
	      {/section}
         </select>
         <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" />
</div>
{/section}
{/let}

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
			<a href={$Child:item.url_alias|ezurl}>{node_view_gui view=line content_node=$Child:item}</a>
                </td>
                <td>
                    {$Child:item.object.class_name|wash}
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

        {section show=eq( $node.sort_array[0][0], 'priority' )}
            {section show=and( $content_object.can_edit,eq( $node.sort_array[0][0], 'priority' ) )}
                 <input class="button" type="submit"  name="UpdatePriorityButton" value="{'Update'|i18n('design/standard/node/view')}" />
            {/section}
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

{default member_groups=fetch( user, member_of, hash( id, $content_object.id ) )}
{section show=count($member_groups)|gt(0)}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>
         {"Member of roles"|i18n("design/standard/node/view")}
    </th>
</tr>
{section loop=$member_groups sequence=array( bglight, bgdark )}
    <tr class="{$:sequence}">
        <td><a href={concat("/role/view/",$:item.id)|ezurl}>{$:item.name|wash}</a></td>
    </tr>
{/section}
</table>

{/section}
{/default}

{section show=ne($node.node_id,5)}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th colspan="3">
         {"Policy list"|i18n("design/standard/node/view")}
    </th>
</tr>
<tr>
    <td><h3>{"Module"|i18n("design/standard/role")}</h3></td>
    <td><h3>{"Function"|i18n("design/standard/role")}</h3></td>
    <td><h3>{"Limitation"|i18n("design/standard/role")}</h3></td>
</tr>
    {section var=Policy loop=$policies sequence=array(bglight,bgdark)}
    <tr class="{$Policy.sequence}">
        <td>
            {$Policy.moduleName}
        </td>
        <td>
            {$Policy.functionName}
        </td>
        <td>
            {section show=eq($Policy.limitation,'*')}
                {$Policy.limitation}
            {section-else}
                {section var=Limitation loop=$Policy.limitation}
                  {$Limitation.identifier|wash}(
                      {section var=LimitationValues loop=$Limitation.values_as_array_with_names}
                          {$LimitationValues.Name|wash}
                          {delimiter}, {/delimiter}
                      {/section})
                      {delimiter}, {/delimiter}
                {/section}
             {/section}
        </td>
    </tr>
    {/section}
</table>
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
{/let}
{/default}
