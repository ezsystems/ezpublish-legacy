{* Show related objects: *}
{section show=$node.object.related_contentobject_count|gt(0)}
    <hr />
    <h2>Related objects: {$node.object.related_contentobject_count}</h2>
    {section var=RelatedObjects loop=$node.object.related_contentobject_array sequence=array( bglight, bgdark )}
        <div class="block">{content_view_gui view=text_linked content_object=$RelatedObjects.item}</div>
    {/section}
{/section}


{* Show reverse related objects: *}
{section show=$node.object.reverse_related_contentobject_count|gt(0)}
        <hr />
    <h2>This object is in use by {$node.object.reverse_related_contentobject_count} other objects:</h2>
    {section var=ReverseRelatedObjects loop=$node.object.reverse_related_contentobject_array sequence=array( bglight, bgdark )}
        <div class="block">{content_view_gui view=text_linked content_object=$ReverseRelatedObjects.item}</div>
    {/section}
{/section}

