{let version=fetch( content, version, hash( object_id, $browse.content.object_id, version_id, $browse.content.object_version ) )
     placement_node=fetch( content, node, hash( node_id, $browse.content.previous_node_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Choose a new location for <%version_name>'|i18n( 'design/admin/content/browse_move_placement',, hash( '%version_name', $version.version_name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">
<p>{'Choose a new location for <%version_name> using the radio buttons then click "Select".'|i18n( 'design/admin/content/browse_move_placement',, hash( '%version_name', $version.version_name ) )|wash}</p>
<p>{'The previous location was <%previous_location>.'|i18n( 'design/admin/content/browse_move_placement',, hash( '%previous_location', $placement_node.name ) )|wash}</p>
<p>{'Navigate using the available tabs (above), the tree menu (left) and the content list (middle).'|i18n( 'design/admin/content/browse_move_placement' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
