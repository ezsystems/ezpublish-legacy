<form action={'/search/stats/'|ezurl} method="post">
{let item_type=ezpreference( 'admin_search_stats_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{'Search statistics'|i18n( 'design/admin/search/stats' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$most_frequent_phrase_array}
{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_search_stats_limit/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_search_stats_limit/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_search_stats_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_search_stats_limit/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_search_stats_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_search_stats_limit/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="right"></div>
<div class="break"></div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th>{'Phrase'|i18n( 'design/admin/search/stats' )}</th>
    <th class="tight">{'Number of phrases'|i18n( 'design/admin/search/stats' )}</th>
    <th class="tight">{'Average result returned'|i18n( 'design/admin/search/stats' )}</th>
</tr>
{section var=Phrases loop=$most_frequent_phrase_array sequence=array( bglight, bgdark )}
<tr class="{$Phrases.sequence}">
    <td>{$Phrases.item.phrase|wash}</td>
    <td class="number" align="right">{$Phrases.item.phrase_count}</td>
    <td class="number" align="right">{$Phrases.item.result_count|l10n( number )}</td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'The list is empty.'|i18n( 'design/admin/search/stats' )}</p>
</div>
{/section}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/search/stats')
         item_count=$search_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">

{if $most_frequent_phrase_array|count}
    <input class="button" type="submit" name="ResetSearchStatsButton" value="{'Reset statistics'|i18n( 'design/admin/search/stats' )}" title="{'Clear the search log.'|i18n( 'design/admin/search/stats' )}" />
{else}
    <input class="button-disabled" type="submit" name="ResetSearchStatsButton" value="{'Reset statistics'|i18n( 'design/admin/search/stats' )}" disabled="disabled" />
{/if}

</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{/let}
</form>
