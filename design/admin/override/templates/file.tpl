{* File admin view template *}

{default with_children=true()
         is_editable=true()
	 is_standalone=true()}
{let page_limit=15
     list_count=and($with_children,fetch( content, list_count, hash( parent_node_id, $node.node_id ) ) )}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}


<div class="objectheader">
    <h2>File</h2>
</div>

<div class="object">
    <h1>{$node_name|wash(xhtml)}</h1>

    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />
    {attribute_view_gui attribute=$node.object.data_map.description}
    <p>{attribute_view_gui attribute=$node.object.data_map.file}</p>

    <div class="buttonblock">
        {section show=$is_editable}
            {switch match=$content_object.can_edit}
            {case match=1}
                <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
                <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
            {/case}
            {case match=0}
            {/case}
            {/switch}
        {/section}
    <input class="button" type="submit" name="ActionPreview" value="Preview" />
    <input class="button" type="submit" name="ActionRemove" value="Remove" />
    <input class="button" type="submit" name="ActionAddToBookmarks" value="{'Bookmark'|i18n('design/standard/node/view')}" />
    <input class="button" type="submit" name="ActionAddToNotification" value="{'Keep me updated'|i18n('design/standard/node/view')}" />

    </div>
</div>

{let name=Object related_objects=$content_version.related_contentobject_array}
{section name=ContentObject  loop=$Object:related_objects show=$Object:related_objects  sequence=array(bglight,bgdark)}
    <div class="block">
        {content_view_gui view=text_linked content_object=$Object:ContentObject:item}
    </div>
{section-else}
{/section}
{/let}

{section show=$is_standalone}
    {section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
        <div class="block">
            <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|wash}" />
        </div>
    {/section}
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
{/let}
{/default}