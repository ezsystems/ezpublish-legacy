{default image_class=medium
         css_class=false()
         alignment=false()
         link_to_image=false()
         href=false()
         target=false()
         hspace=false()
         border_size=0}
{let image_content=$object.data_map.image.content}

{section show=$image_content.is_valid}

    {section show=is_set($attribute_parameters.size)}
        {set image_class=$attribute_parameters.size}
    {/section}

    {section show=is_set($attribute_parameters.align)}
        {set alignment=$attribute_parameters.align}
    {section-else}
        {set alignment="center"}
    {/section}

    {section show=is_set($attribute_parameters.href)}
        {set href=$attribute_parameters.href}
    {/section}

    {section show=is_set($attribute_parameters.target)}
        {set target=$attribute_parameters.target}
    {/section}

    {let image=$image_content[$image_class]}

    {section show=$link_to_image}
        {let image_original=$image_content['original']}
        {set href=$image_original.url|ezroot}
        {/let}
    {/section}
    {switch match=$alignment}
    {case match='left'}
        <div class="imageleft">
    {/case}
    {case match='right'}
        <div class="imageright">
    {/case}
    {case match='center'}
        <div class="imagecenter">
    {/case}
    {case/}
    {/switch}

    {section show=$css_class}
        <div class="{$css_class|wash}">
    {/section}

    {section show=$href}<a href={$href}{section show=$target} target="{$target}"{/section}>{/section}<img src={$image.url|ezroot} "width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} border="{$border_size}" alt="{$image.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />{section show=$href}</a>{/section}

    {section show=$css_class}
        </div>
    {/section}

    {switch match=$alignment}
    {case match='left'}
        </div>
    {/case}
    {case match='right'}
        </div>
    {/case}
    {case match='center'}
        </div>
    {/case}
    {case/}
    {/switch}

    {/let}

{/section}

{/let}

{/default}


