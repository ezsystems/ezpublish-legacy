{switch match=$attribute.content.contentobject_attribute_id}
  {case match=""}
  {/case}
  {case}
  {default image_class=large
         alignment=false()
         hspace=false()
         border_size=0}
	 {let content=$attribute.content
     image=$content[$image_class]}
     <img src={concat("/var/storage/variations/",$content.mime_type_category,"/",$image.additional_path,$image.filename)|ezroot} width="{$image.width}" height="{$image.height}" {section show=$hspace}hspace="{$hspace}"{/section} {section show=$alignment}align="{$alignment}"{/section} border="{$border_size}" />
     {/let}
  {/default}
  {/case}
{/switch}