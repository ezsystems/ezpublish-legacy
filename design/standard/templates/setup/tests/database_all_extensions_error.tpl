{let db_results=$test_result[2]}

<h3>{$result_number}. Missing database handlers</h3>
<p>
 Your PHP does not have support for all databases that eZ publish support.
 Allthough eZ publish will work without it, it might be that you want to have support
 for this database.
 Also some databases has more advanced features, such as charset, than others.
</p>
<p>
To obtain more database support you need to recompile PHP, the exact recompile options are specified below.
</p>
<table width="100%" cellspacing="0" cellpadding="8">
{section name=DB loop=$db_results.failed_extensions sequence=array(bglight,bgdark)}
<tr>
  <td class="{$:sequence}">{include uri=concat('design:setup/db/',$:item,'_info.tpl')}</td>
</tr>
{/section}
</table>

{/let}