{let image_variation="false"
     align="center"
     href=''}

{if is_set($attribute_parameters.size)}
{set image_variation=$object.data_map.image.content[$attribute_parameters.size]}
{else}
{set image_variation=$object.data_map.image.content["medium"]}
{/if}

{if is_set($attribute_parameters.align)}
{set align=$attribute_parameters.align}
{else}
{set align="center"}
{/if}

{if is_set($attribute_parameters.href)}
{set href=$attribute_parameters.href}
{else}
{set href=""}
{/if}

{switch match=$align}
{case match="left"}
<div class="imageleft">
{if $href}<a href={$href|ezurl}>{/if}
<img src={$image_variation.full_path|ezroot} />
{if $href}</a>{/if}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{case match="right"}
<div class="imageright">
{if $href}<a href={$href|ezurl}>{/if}
<img src={$image_variation.full_path|ezroot} />
{if $href}</a>{/if}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{case}
<div class="imagecenter">
{if $href}<a href={$href|ezurl}>{/if}
<img src={$image_variation.full_path|ezroot} />
{if $href}</a>{/if}

<div style="width: {$image_variation.width}px;">
{$object.data_map.caption.content.output.output_text}
</div>
</div>
{/case}
{/switch}

{/let}
