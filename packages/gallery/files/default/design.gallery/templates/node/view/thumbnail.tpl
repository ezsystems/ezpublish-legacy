{* Image thumbnail template *}
{let content=$node.object.data_map.image.content}
<img src={$content["thumbnail"].full_path|ezroot} alt="{$content.alternative_text|wash(xhtml)}" />
{/let}