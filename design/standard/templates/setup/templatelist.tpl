<h1>Template list</h1>


<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
   <td class="bgdark">
   Template
   </td>
   <td class="bgdark">
   Design Resource
   </td>
</tr>
{section name=Template loop=$template_array sequence=array(bglight,bgdark)}
<tr>
   <td class="{$Template:sequence}"> 
   <p>
   <a href={concat('/setup/templateview',$Template:item.template)|ezurl}>{$Template:item.template}</a>
   </p>
   </td>
   <td class="{$Template:sequence}">
   <p>
   {$Template:item.base_dir}
   </p>
   </td>
   <td class="{$Template:sequence}">
   Edit
   </td>
</tr>
{/section}
</table>
 