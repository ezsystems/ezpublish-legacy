{let object=$node.object
     map=$object.data_map}
<p class="heading">
  <strong><a href={concat("content/view/full/",$node.node_id)|ezurl}>{attribute_view_gui attribute=$map.title}</a></strong>
</p>
<p>
  <a href={concat("content/view/full/",$node.node_id)|ezurl}>{attribute_view_gui attribute=$map.photo border_size=1 hspace=10 alignment=right image_class=medium}</a>
  {attribute_view_gui attribute=$map.intro}
</p>
<a class="small" href={concat("content/view/full/",$node.node_id)|ezurl}>Product information</a>

{/let}