{default with_children=false()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name
         parent_nodes=$content_object.parent_nodes}
{let data_map=$node.object.data_map
     user_attribute=$data_map.user_account
     has_extra_groups=false()
     policies=fetch( 'user', 'user_role', hash( user_id, $node.object.id ) ) }

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}


<div class="objectheader">
    <h2>{$node_name|wash} &lt;{$user_attribute.content.email|wash}&gt; [{'User'|i18n('design/admin/node/view')}], {'Node ID'|i18n( 'design/standard/node/view' )}: {$node.node_id}, {'Object ID'|i18n( 'design/standard/node/view' )}: {$node.object.id}</h2>
</div>

<div class="object">
    <p>{node_view_gui view=text_linked content_node=$content_object.main_node.parent}</p>
    <p>Login: <i>{$user_attribute.content.login|wash}</i></p>
    <p>User ID: <i>{$user_attribute.content.contentobject_id}</i></p>
    <p>User <a href={concat( "user/setting/", $user_attribute.content.contentobject_id )|ezurl}>settings</a></p>
    {section show=and( is_set( $data_map.image ), $data_map.image.has_content )}
    <p>Image:</p>{attribute_view_gui attribute=$data_map.image}
    {/section}
    {section show=is_set( $data_map.signature )}
    <p>Signature:</p>{$data_map.signature.content|simpletags|autolink}
    {/section}

    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />

    {section loop=$parent_nodes}
    {section-exclude match=eq($:item,$content_object.main_node.parent_node_id)}
        {set has_extra_groups=true()}
    {/section}

    {section show=$has_extra_groups}
        <p>{'Also part of these groups'|i18n('design/admin/node/view')}</p>
        {section name=Parent loop=$parent_nodes}
        {section-exclude match=eq($:item,$content_object.main_node.parent_node_id)}
            <p>{node_view_gui view=text_linked content_node=fetch(content,node,hash(node_id,$:item))}</p>
        {/section}
    {/section}

    <div class="buttonblock">
    {section show=and($is_editable,$content_object.can_edit)}
        <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
    {/section}
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" />
    </div>
</div>

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

{section show=count( $policies )}
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

{/let}
{/default}
