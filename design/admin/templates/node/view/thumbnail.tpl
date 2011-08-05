<div class="content-view-thumbnail">
{def $can_create_classes = $node.classes_js_array $disable_createHere_menu = cond( $can_create_classes|eq("''"),"'child-menu-create-here'", '-1' ) }
<a href={$node.url_alias|ezurl} onclick="ezpopmenu_showTopLevel( event, 'SubitemsContextMenu', ez_createAArray( new Array( '%nodeID%', {$:node.node_id}, '%objectID%', {$:node.object.id}, '%languages%', {$:node.object.language_js_array|wash}, '%classList%', {$can_create_classes|wash} ) ) , '{$:node.object.name|shorten(18)|wash}', {$:node.node_id}, {$disable_createHere_menu} ); return false;">{$node.class_identifier|class_icon( normal, '[%classname] Click on the icon to display a context-sensitive menu.'|i18n( 'design/admin/node/view/thumbnail',, hash( '%classname', $node.class_name ) ) )}</a>
{undef $can_create_classes $disable_createHere_menu}
</div>
