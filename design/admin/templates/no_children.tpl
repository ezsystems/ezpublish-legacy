<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title"><a href={$node.parent.url_alias|ezurl}><img src={'up-16x16-grey.png'|ezimage} width="16" height="16" alt="{'Up one level'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level'|i18n( 'design/admin/node/view/full' )}" /></a> {'Sub items (%children_count)'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $node.children_count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<div class="block">
    <p>{'The <%class_name> class is not configured to contain any sub items.'|i18n( 'design/admin/node/view/full',, hash( '%class_name', $node.class_name ) )|wash}</p>
</div>

{* DESIGN: Content END *}</div></div></div>

</div>
