
  {default image_class=large
           alignment=false()
	   hspace=false()
           border_size=0}
  {let image_content=$attribute.content
       image=$image_content[$image_class]}

       {pdf(image,hash(src,$image.full_path|ezroot(no),
                       width,$image.width,
		       height,$image.height,
		       border,$border_size,
		       text,$image_class|wash(pdf)))}

  {/let}
  {/default}
