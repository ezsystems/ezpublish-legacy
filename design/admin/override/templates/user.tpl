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
    <h2>User</h2>
</div>

<div class="object">
    <h1>{$node_name|wash( xhtml )}</h1>

    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />

    <p>{attribute_view_gui attribute=$node.object.data_map.user_account}</p>
            
    <div class="buttonblock">
    {section show=$is_editable}
        {switch match=$content_object.can_edit}
        {case match=1}
            <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
            <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/standard/node/view' )}" />
        {/case}
        {case match=0}
        {/case}
        {/switch}
    {/section}
    <input class="button" type="submit" value="Remove" />
    </div>
</div>

{section show=$is_standalone}
    </form>
{/section}

{/default}
{/let}
{/default}
