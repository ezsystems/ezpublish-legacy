<div class="content-view-embeddedmedia">
<div class="class-image">

<div class="attribute-image">
<p>
{section show=is_set($link_parameters.href)}
  {section show=is_set($object_parameters.size)}
    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
  {section-else}
    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' ) href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
  {/section}
{section-else}
  {section show=is_set($object_parameters.size)}
    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size}
  {section-else}
    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' )}
  {/section}
{/section}
</p>
</div>

{section show=$object.data_map.caption.has_content}
{section show=is_set($object.data_map.image.content[$object_parameters.size].width)}
<div class="attribute-caption" style="width: {$object.data_map.image.content[$object_parameters.size].width}px">
{section-else}
<div class="attribute-caption">
{/section}
    {attribute_view_gui attribute=$object.data_map.caption}
</div>
{/section}
</div>
</div>

