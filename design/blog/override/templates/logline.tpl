<div class="logline">

<h2>{$node.object.published|datetime('custom', '%l | %j %F %Y')}</h2>
 <div class="logentry">
    <h3>{$node.name}</h3>
    {attribute_view_gui attribute=$node.object.data_map.log}
    {$node.object.published|datetime('custom', '%g:%i%a')} in {$node.parent.name} | xx comments
  </div>
</div>