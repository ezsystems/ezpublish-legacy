<form action={'/search/stats/'|ezurl} method="post">

<div class="context-block">
<h2 class="context-title">{'Search statistics'|i18n('design/admin/search')}</h2>

<table class="list" cellspacing="0">
<tr>
	<th>
	{'Phrase'|i18n( 'design/admin/search' )}
	</th>
	<th>
	{'Number of phrases'|i18n( 'design/admin/search' )}
	</th>
	<th>
	{'Average result returned'|i18n( 'design/admin/search' )}
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
</table>

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="ResetSearchStatsButton" value="{'Reset statistics'|i18n( 'design/admin/search' )}" />
</div>
</div>

</div>

</form>
