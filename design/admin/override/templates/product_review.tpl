{* Product review admin view template *}

{default with_children=false()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action"|ezurl}>
{/section}

<div class="objectheader">
    <h2>{'Product review'|i18n('design/admin/node/view')}</h2>
</div>

<div class="object">
    <h1>{$node_name|wash}</h1>
    <input type="hidden" name="TopLevelNode" value="{$content_object.main_node_id}" />
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />

    <p class="small">{attribute_view_gui attribute=$node.object.data_map.reviewer_name}<br />
    {attribute_view_gui attribute=$node.object.data_map.geography}</p>
    {attribute_view_gui attribute=$node.object.data_map.review}
    <p class="rating">{$node.object.data_map.rating.contentclass_attribute.name}: {attribute_view_gui attribute=$node.object.data_map.rating}</p>

    <div class="buttonblock">
        {section show=and($is_editable,$content_object.can_edit)}
            <input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
            <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
        {/section}
    <input class="button" type="submit" name="ActionPreview" value="{'Preview'|i18n('design/standard/node/view')}" />
    <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n('design/standard/node/view')}" />
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
            <input class="button" type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|wash}" />
        </div>
    {/section}
{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}