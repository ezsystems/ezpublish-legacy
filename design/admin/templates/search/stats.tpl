<form action={'/search/stats/'|ezurl} method="post">

<div class="context-block">
<h2 class="context-title">{'Search statistics'|i18n( 'design/admin/search/stats' )}</h2>

<table class="list" cellspacing="0">
{section show=$most_frequent_phrase_array|count}
<tr>
	<th>
	{'Phrase'|i18n( 'design/admin/search/stats' )}
	</th>
	<th>
	{'Number of phrases'|i18n( 'design/admin/search/stats' )}
	</th>
	<th>
	{'Average result returned'|i18n( 'design/admin/search/stats' )}
	</th>
</tr>
{section var=Phrases loop=$most_frequent_phrase_array sequence=array( bglight, bgdark )}
<tr class="{$Phrases.sequence}">
	<td>
	{$Phrases.item.phrase|wash}
	</td>
	<td>
	{$Phrases.item.phrase_count}
	</td>
	<td>
	{$Phrases.item.result_count|l10n( number )}
	</td>
</tr>
{/section}
{section-else}
<tr><td>{'The statistics list is emtpy.'|i18n( 'design/admin/search/stats' )}</td></tr>
{/section}


</table>

<div class="controlbar">
<div class="block">

{section show=$most_frequent_phrase_array|count}
    <input class="button" type="submit" name="ResetSearchStatsButton" value="{'Reset statistics'|i18n( 'design/admin/search/stats' )}" />
{section-else}
    <input class="button" type="submit" name="ResetSearchStatsButton" value="{'Reset statistics'|i18n( 'design/admin/search/stats' )}" disabled="disabled" />
{/section}

</div>
</div>

</div>

</form>
