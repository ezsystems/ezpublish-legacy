{let image_variation="false"
     align="center"
     href=''
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

{section show=is_set($attribute_parameters.href)}
{set href=$attribute_parameters.href}
{section-else}
{set href=""}
{/section}

{switch match=$align}
{case match="left"}
<div class="imageleft">
{section show=$href}<a href={$href|ezurl} target="{$attribute_parameters.target}">{/section}
<img src={$image_variation.full_path|ezroot} alt="{$object.data_map.image.content.alternative_text|wash(xhtml)}" />
{section show=$href}</a>{/section}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{case match="right"}
<div class="imageright">
{section show=$href}<a href={$href|ezurl} target="{$attribute_parameters.target}">{/section}
<img src={$image_variation.full_path|ezroot} alt="{$object.data_map.image.content.alternative_text|wash(xhtml)}" />
{section show=$href}</a>{/section}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{case}

<div class="imagecenter">
{section show=$href}<a href={$href|ezurl} target="{$attribute_parameters.target}">{/section}
<img src={$image_variation.full_path|ezroot} alt="{$object.data_map.image.content.alternative_text|wash(xhtml)}"  />
{section show=$href}</a>{/section}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{/switch}

{/let}
