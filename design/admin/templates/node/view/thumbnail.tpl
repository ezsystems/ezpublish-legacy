<div class="content-view-thumbnail">
<a href={$node.url_alias|ezurl} onclick="ezpopmenu_showTopLevel( event, 'SubitemsContextMenu', ez_createAArray( new Array( '%nodeID%', {$:node.node_id}, '%objectID%', {$:node.object.id} ) ) , '{$:node.object.name|shorten(18)|wash}', {$:node.node_id} ); return false;">{$node.class_identifier|class_icon( normal, '[%classname] Click on the icon to get a context sensitive menu.'|i18n( 'design/admin/node/view/thumbnail',, hash( '%classname', $node.class_name ) ) )}</a>
</div>
