<div class="context-block">

<h2 class="title">Relations</h2>

{* Show related objects: *}
<table class="list" cellspacing="0">
<tr>
    <th>Related objects</th>
</tr>
{section show=$node.object.related_contentobject_count|gt(0)}
    {section var=RelatedObjects loop=$node.object.related_contentobject_array sequence=array( bglight, bgdark )}
    <tr class="{$RelatedObjects.sequence}">
        <td>{$RelatedObjects.item.node.object.content_class.identifier|class_icon( small, $RelatedObjects.item.node.object.content_class.name )}&nbsp;{content_view_gui view=text_linked content_object=$RelatedObjects.item}</td>
    </tr>
    {/section}
{/section}
</tr>
</table>

{* Show reverse related objects: *}
<table class="list" cellspacing="0">
<tr>
    <th>Object used by:</th>
</tr>
{section show=$node.object.reverse_related_contentobject_count|gt(0)}
    {section var=ReverseRelatedObjects loop=$node.object.reverse_related_contentobject_array sequence=array( bglight, bgdark )}
    <tr class="{$ReverseRelatedObjects.sequence}">
        <td>{$ReverseRelatedObjects.item.node.object.content_class.identifier|class_icon( small, $ReverseRelatedObjects.item.node.object.content_class.name )}&nbsp;{content_view_gui view=text_linked content_object=$ReverseRelatedObjects.item}</td>
    </tr>
    {/section}
{/section}
</tr>
</table>

</div>