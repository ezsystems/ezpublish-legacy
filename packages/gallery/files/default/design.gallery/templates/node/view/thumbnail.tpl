{* Image thumbnail template *}

<div id="image">
{let content=$node.object.data_map.image.content}
<img src={$content["thumbnail"].full_path|ezroot} alt="{$content.alternative_text|wash(xhtml)}" />
{/let}
{attribute_view_gui attribute=$node.object.data_map.caption}

    <div class="commentlist">
       {section loop=$comments}
           {node_view_gui view=line content_node=$:item}
       {/section}
    </div>
</div>
