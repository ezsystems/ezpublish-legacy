{*{section show=and(first_set($attribute.content,true()),first_set($attribute.content.contentobject_attribute_id,false()))}*}
  {default image_class=large
           alignment=false()
           hspace=false()
           border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]
       image2=$image_content['zoom']}
     <img src={$image.full_path|ezroot} width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} {section show=$alignment}align="{$alignment}"{/section} border="{$border_size}" alt="{$image.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />
     <img src={$image2.full_path|ezroot} width="{$image2.width}" height="{$image2.height}" {section show=$hspace}hspace="{$hspace}"{/section} {section show=$alignment}align="{$alignment}"{/section} border="{$border_size}" alt="{$image2.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />
  {/let}
  {/default}
{*{/section}*}
