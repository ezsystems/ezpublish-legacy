{let image_variation="false"
     align="center"}

{section show=is_set( $attribute_parameters.size )}
    {set image_variation=$object.data_map.image.content[$attribute_parameters.size]}
{section-else}
    {set image_variation=$object.data_map.image.content["medium"]}
{/section}

{section show=is_set( $attribute_parameters.align )}
    {set align=$attribute_parameters.align}
{section-else}
    {set align="center"}
{/section}

{switch match=$align}
{case match="left"}
    <div class="imageleft">
    <img src={$image_variation.full_path|ezroot} />
    <div style="width: {$image_variation.width}px;">
    {$object.data_map.caption.content.output.output_text}
    </div>
    </div>
{/case}
{case match="right"}
    <div class="imageright">
    <img src={$image_variation.full_path|ezroot} />
    <div style="width: {$image_variation.width}px;">
    {$object.data_map.caption.content.output.output_text}
    </div>
    </div>
{/case}
{case}
    <div class="imagecenter">
    <img src={$image_variation.full_path|ezroot} />
    <div style="width: {$image_variation.width}px;">
    {$object.data_map.caption.content.output.output_text}
    </div>
    </div>
{/case}
{/switch}

{/let}