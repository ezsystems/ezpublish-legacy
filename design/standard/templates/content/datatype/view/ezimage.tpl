{default image_class=large
         alignment=false()
         link_to_image=false()
         href=false()
         hspace=false()
         border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]}
  {section show=$link_to_image}
  {let image_original=$image_content['original']}
      {set href=$image_original.url|ezroot}
  {/let}
  {/section}
     {section show=$href}<a href={$href}>{/section}<img src={$image.url|ezroot} width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} {section show=$alignment}align="{$alignment}"{/section} border="{$border_size}" alt="{$image.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />{section show=$href}</a>{/section}
  {/let}
{/default}
