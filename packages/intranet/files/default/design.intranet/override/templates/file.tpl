{* File template *}

<div class="maincontentheader">
    <h1>{$node.object.name|wash}</h1>
</div>

{attribute_view_gui attribute=$node.object.data_map.description}
{attribute_view_gui attribute=$node.object.data_map.file}