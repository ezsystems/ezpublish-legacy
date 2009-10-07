<div class="content-view-embeddedmedia">
<div class="class-image">

<div class="attribute-image">
<p>
{if is_set($link_parameters.href)}
  {if is_set($object_parameters.size)}
    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
  {else}
    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' ) href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
  {/if}
{else}
  {if is_set($object_parameters.size)}
    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size}
  {else}
    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' )}
  {/if}
{/if}
</p>
</div>

{if $object.data_map.caption.has_content}
{if is_set($object.data_map.image.content[$object_parameters.size].width)}
<div class="attribute-caption" style="width: {$object.data_map.image.content[$object_parameters.size].width}px">
{else}
<div class="attribute-caption">
{/if}
    {attribute_view_gui attribute=$object.data_map.caption}
</div>
{/if}
</div>
</div>

