{let class=fetch( content, class, hash( class_id, $browse.content.class_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Choose location for new <%classname>'|i18n( 'design/admin/content/browse_first_placement',, hash( '%classname', $class.name) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<div class="block">
<p>{'Choose a location for the new <%classname> using the radio buttons then click "Select".'|i18n( 'design/admin/content/browse_first_placement',, hash( '%classname', $class.name) )|wash}</p>
<p>{'Navigate using the available tabs (above), the tree menu (left) and the content list (middle).'|i18n( 'design/admin/content/browse_first_placement' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div>

</div>

{/let}
