<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

{* DESIGN: Mainline and Template filter form START*}
<table cellspacing="0" width="100%" >
<tr>
   <td>
    <h1 class="context-title">{'Template list'|i18n( 'design/admin/visual/templatelist' )}</h1>
   </td>
   <th align=right >
    <div class="content-search" >
           <form action={"/visual/templatelist"|ezurl} method="get">
               <input class="halfbox" type="text" size="15" name="filterString" id="Filter" value="{$filterString}" />
               <input class="button" name="FilterButton" type="submit" value="{'Filter'|i18n('design/standard/layout')}" />&nbsp; &nbsp;
           </form>
    </div>
   </th>
</tr>
</table>

{* DESIGN: Mainline and Template filter form END *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
   <th>
   {'Template'|i18n( 'design/admin/visual/templatelist' )}
   </th>
   <th>
   {'Design resource'|i18n( 'design/admin/visual/templatelist' )}
   </th>
</tr>
{section var=Templates max=20 offset=$view_parameters.offset loop=$template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/visual/templateview', $Templates.item.template )|ezurl} title="{'Manage overrides for template.'|i18n( 'design/admin/visual/templatelist' )}">{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/visual/templatelist'
         page_uri_suffix=concat('?filterString=',$filterString|urlencode )    
         item_count=$template_count
         view_parameters=$view_parameters
         item_limit=20}
</div>

{* DESIGN: Content END *}</div></div></div>

</div>



<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title">{'Most common templates'|i18n( 'design/admin/visual/templatelist' )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
   <th>{'Template'|i18n( 'design/admin/visual/templatelist' )}</th>
   <th>{'Design resource'|i18n( 'design/admin/visual/templatelist' )}</th>
</tr>
{section var=Templates loop=$most_used_template_array sequence=array( bglight, bgdark )}
<tr class="{$Templates.sequence}">
   <td><a href={concat( '/visual/templateview', $Templates.item.template )|ezurl} title="{'Manage overrides for template.'|i18n( 'design/admin/visual/templatelist' )}">{$Templates.item.template}</a></td>
   <td>{$Templates.item.base_dir}</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

</div>
