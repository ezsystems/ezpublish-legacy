{default image_class=large
         border_size=0}
{let content=$attribute.content
     image=$content[$image_class]}
<img src="/var/storage/variations/{$content.mime_type_category}/{$image.additional_path}{$image.filename}" width={$image.width} height={$image.height} border="{$border_size}" />
{/let}
{/default}