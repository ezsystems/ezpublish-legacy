{let db_results=$test_result[2]}

<h3>{$result_number}. Missing database handler</h3>
<p>No supported database handlers were found. eZ publish requires a database to store
it's data, without one the system will fail.</p>
<p>
To obtain database support you need to recompile PHP, the exact recompile options are specified below.
</p>
<table width="100%" cellspacing="0" cellpadding="8">
{section name=DB loop=$db_results.failed_extensions sequence=array(bglight,bgdark)}
<tr>
  <td class="{$:sequence}">{include uri=concat('design:setup/db/',$:item,'_info.tpl')}</td>
</tr>
{/section}
</table>

{/let}