<h1>Search statistics</h1>


<h2>Most frequent search phrases</h2>
<table width="100%">
<tr>
	<th>
	Phrase	
	</th>
	<th>
	Number of phrases
	</th>
	<th>
	Avg, result returned
	</th>
</tr>
{section name=Phrase loop=$most_frequent_phrase_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$SearchResult:sequence}">
	{$Phrase:item.phrase}
	</td>
	<td class="{$SearchResult:sequence}">
	{$Phrase:item.phrase_count}
	</td>
	<td class="{$SearchResult:sequence}">
	{$Phrase:item.result_count|l10n(number)}
	</td>
</tr>
{/section}
</table>