<div class="link">
  <h2><a href={concat( $node.data_map.url.content )|ezurl} >{$node.name|wash}</a></h2>
  <div class="linkentry">
    {attribute_view_gui attribute=$node.data_map.description}
  </div>
</div>
