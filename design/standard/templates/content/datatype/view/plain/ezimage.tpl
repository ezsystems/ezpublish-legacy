{let image_content=$attribute.content
       image=$image_content[$image_class]}
{$image.full_path|ezroot}
{$image_content.alternative_text|wash(xhtml)}
{/let}
