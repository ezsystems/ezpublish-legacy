<div class="context-block">
<h2 class="context-title">{'Complete template list'|i18n( 'design/admin/setup/templatelist' )}</h2>

<table class="list" cellspacing="0">
<tr>
   <th>
   {'Template'|i18n( 'design/admin/setup/templatelist' )}
   </th>
   <th>
   {'Design resource'|i18n( 'design/admin/setup/templatelist' )}
   </th>
</tr>
{section var=Templates max=20 offset=$view_parameters.offset loop=$template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/setup/templateview', $Templates.item.template )|ezurl}>{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/setup/templatelist'
         item_count=$template_count
         view_parameters=$view_parameters
         item_limit=20}
</div>
</div>


<div class="context-block">
<h2 class="context-title">{'Most common templates'|i18n( 'design/admin/setup/templatelist' )}</h2>

<table class="list" cellspacing="0">
<tr>
   <th>{'Template'|i18n( 'design/admin/setup/templatelist' )}</th>
   <th>{'Design resource'|i18n( 'design/admin/setup/templatelist' )}</th>
</tr>
{section var=Templates loop=$most_used_template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/setup/templateview', $Templates.item.template )|ezurl}>{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

</div>
