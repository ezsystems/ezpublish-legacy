{let content_object=fetch( content, object, hash( object_id, $browse.content.object_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Choose the node to exchange for <%object_name>'|i18n( 'design/admin/content/browse_swap_node',, hash( '%object_name', $content_object.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">
<p>{'Use the radio buttons to choose the node that you want to swap with <%object_name>.'|i18n( 'design/admin/content/browse_swap_node',, hash( '%object_name', $content_object.name ) )|wash}</p>
<p>{'Navigate using the available tabs (above), the tree menu (left) and the content list (middle).'|i18n( 'design/admin/content/browse_swap_node' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
