<h1>Template list</h1>

<h2>Most common templates</h2>

<table class="list" width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
   <th>
   Template
   </th>
   <th>
   Design Resource
   </th>
</tr>
{section name=Template loop=$most_used_template_array sequence=array(bglight,bgdark)}
<tr>
   <td class="{$Template:sequence}"> 
   <a href={concat('/setup/templateview',$Template:item.template)|ezurl}>{$Template:item.template}</a>
   </td>
   <td class="{$Template:sequence}">
   {$Template:item.base_dir}
   </td>
</tr>
{/section}
</table>

<h2>Complete template list</h2>

<table class="list" width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
   <th>
   Template
   </th>
   <th>
   Design Resource
   </th>
</tr>
{section max=20 offset=$view_parameters.offset name=Template loop=$template_array sequence=array(bglight,bgdark)}
<tr>
   <td class="{$Template:sequence}"> 
   <a href={concat('/setup/templateview',$Template:item.template)|ezurl}>{$Template:item.template}</a>
   </td>
   <td class="{$Template:sequence}">
   {$Template:item.base_dir}
   </td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/setup/templatelist')
         item_count=$template_count
         view_parameters=$view_parameters
         item_limit=20}
 