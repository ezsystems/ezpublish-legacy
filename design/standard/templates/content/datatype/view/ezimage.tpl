{default image_class=large
         alignment=false()
         link_to_image=false()
         href=false()
         hspace=false()
         border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]}

{section show=image_content.is_valid}

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
         {case/}
     {/switch}

     {section show=$href}<a href={$href}>{/section}<img src={$image.url|ezroot} width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} border="{$border_size}" alt="{$image.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />{section show=$href}</a>{/section}

     {switch match=$alignment}
         {case match='left'}
          </div>
         {/case}
         {case match='right'}
          </div>
         {/case}
         {case/}
     {/switch}

{/section}

  {/let}
{/default}
