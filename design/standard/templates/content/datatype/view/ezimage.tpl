{section show=and(first_set($attribute.content,true()),first_set($attribute.content.contentobject_attribute_id,false()))}
  {default image_class=large
           alignment=false()
           hspace=false()
           border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]}
     <img src={$image.full_path|ezroot} width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} {section show=$alignment}align="{$alignment}"{/section} border="{$border_size}" alt="{$image_content.alternative_text|wash(xhtml)}" />
  {/let}
  {/default}
{/section}
