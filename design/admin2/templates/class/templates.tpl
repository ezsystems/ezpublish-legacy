{*-- Override templates start. --*}
{let override_templates=fetch( class, override_template_list, hash( class_id, $class.id ) )}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Override templates [%1]'|i18n( 'design/admin/class/view',, array( $override_templates|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=$override_templates|count}
<table class="list" cellspacing="0">
<tr>
    <th>{'Siteaccess'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Source template'|i18n( 'design/admin/class/view' )}</th>
    <th>{'Override template'|i18n( 'design/admin/class/view' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Overrides loop=$override_templates sequence=array( bglight, bgdark )}
<tr class="{$Overrides.sequence}">
    <td>{$Overrides.item.siteaccess}</td>
    <td>{$Overrides.item.block}</td>
    <td><a href={concat( '/visual/templateview', $Overrides.item.source )|ezurl} title="{'View template overrides for the <%source_template_name> template.'|i18n( 'design/admin/class/view',, hash( '%source_template_name', $Overrides.item.source ) )|wash}">{$Overrides.item.source}</a></td>
    <td>{$Overrides.item.target}</td>
    <td><a href={concat( '/visual/templateedit/', $Overrides.item.target)|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/class/view' )}" title="{'Edit the override template for the <%override_name> override.'|i18n( 'design/admin/class/view',, hash( '%override_name', $Overrides.item.block ) )|wash}" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>
{'This class does not have any class-level override templates.'|i18n( 'design/admin/class/view' )}
</p>
</div>
{/section}
{*DESIGN: Content END *}</div></div></div></div></div></div>

</div>
{/let}
{*-- Override templates end. --*}

