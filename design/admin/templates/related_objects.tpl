{* Related objects window. *}
<div class="context-block">

<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Relations'|i18n( 'design/admin/node/view/full')}</h2>

<div class="header-subline"></div>

</div></div>
</div></div></div>
</div>

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

{* Related objects list. *}
<table class="list" cellspacing="0">
<tr>
    <th>{'Related objects [%related_objects_count]'|i18n( 'design/admin/node/view/full',, hash( '%related_objects_count', $node.object.related_contentobject_count ) )}</th>
</tr>
{section show=$node.object.related_contentobject_count}
    {section var=RelatedObjects loop=$node.object.related_contentobject_array sequence=array( bglight, bgdark )}
        <tr class="{$RelatedObjects.sequence}">
        <td>{$RelatedObjects.item.content_class.identifier|class_icon( small, $RelatedObjects.item.content_class.name )}&nbsp;{content_view_gui view=text_linked content_object=$RelatedObjects.item}</td>
        </tr>
{/section}
{section-else}
<tr><td>{'The item being viewed does not make use of any other objects.'|i18n( 'design/admin/node/view/full' )}</td></tr>
{/section}
</table>

{* Reverse related objects list. *}
<table class="list" cellspacing="0">
<tr>
    <th>{'Reverse related objects'|i18n( 'design/admin/node/view/full' )} [{$node.object.reverse_related_contentobject_count}]</th>
</tr>
{section show=$node.object.reverse_related_contentobject_count}
    {section var=ReverseRelatedObjects loop=$node.object.reverse_related_contentobject_array sequence=array( bglight, bgdark )}
        <tr class="{$ReverseRelatedObjects.sequence}">
        <td>{$ReverseRelatedObjects.content_class.identifier|class_icon( small, $ReverseRelatedObjects.item.content_class.name )}&nbsp;{content_view_gui view=text_linked content_object=$ReverseRelatedObjects.item}</td>
        </tr>
    {/section}
{section-else}
<tr><td>{'The item being viewed is not in use by any other objects.'|i18n( 'design/admin/node/view/full' )}</td></tr>
{/section}
</table>

</div>
</div></div>
</div></div></div>

</div>
