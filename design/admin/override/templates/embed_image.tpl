{default $object_parameters=array()}
{let image_variation="false"
     align="center"
     attribute_parameters=$object_parameters}
{section show=is_set($attribute_parameters.size)}
{set image_variation=$object.data_map.image.content[$attribute_parameters.size]}
{section-else}
{set image_variation=$object.data_map.image.content[ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' )]}
{/section}
{section show=is_set($attribute_parameters.align)}
{set align=$attribute_parameters.align}
{section-else}
{set align="center"}
{/section}

<div class="image{$align}">
{section show=is_set($link_parameters.href)}<a href={$link_parameters.href|ezurl} target="{$link_parameters.target}">{/section}
<img src={$image_variation.full_path|ezroot} alt="{$object.data_map.image.content.alternative_text|wash(xhtml)}" />
{section show=is_set($link_parameters.href)}</a>{/section}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>

{/let}
{/default}
