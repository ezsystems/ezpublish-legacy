{section show=and(first_set($attribute.content,true()),first_set($attribute.content.contentobject_attribute_id,false()))}
  {default image_class=large
           alignment=false()
           border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]}

       {pdf(image,hash(src,$image.full_path,
                       width,$image.width,
		       height,$image.height,
		       border,$border_size))}

  {/let}
  {/default}
{/section}