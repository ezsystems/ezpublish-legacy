<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Complete template list'|i18n( 'design/standard/design/templatelist' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
   <th>
   {'Template'|i18n( 'design/standard/design/templatelist' )}
   </th>
   <th>
   {'Design resource'|i18n( 'design/standard/design/templatelist' )}
   </th>
</tr>
{section var=Templates max=20 offset=$view_parameters.offset loop=$template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/design/templateview', $Templates.item.template )|ezurl}>{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/design/templatelist'
         item_count=$template_count
         view_parameters=$view_parameters
         item_limit=20}
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>



<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Most common templates'|i18n( 'design/standard/design/templatelist' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
   <th>{'Template'|i18n( 'design/standard/design/templatelist' )}</th>
   <th>{'Design resource'|i18n( 'design/standard/design/templatelist' )}</th>
</tr>
{section var=Templates loop=$most_used_template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/design/templateview', $Templates.item.template )|ezurl}>{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
