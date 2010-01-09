{let section=fetch( section, object, hash( section_id, $browse.content.section_id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Choose start location for the <%section_name> section'|i18n( 'design/admin/section/browse_assign',, hash( '%section_name', $section.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">
<p>{'Use the radio buttons to select an item that should have the <%section_name> section assigned.'|i18n( 'design/admin/section/browse_assign',, hash( '%section_name', $section.name ) )|wash}</p>
<p>{'Note that the section assignment of the sub items will also be changed.'|i18n( 'design/admin/section/browse_assign' )}</p>
<p>{'Navigate using the available tabs (above), the tree menu (left) and the content list (middle).'|i18n( 'design/admin/section/browse_assign' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
