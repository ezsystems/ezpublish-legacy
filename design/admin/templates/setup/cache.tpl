{* Feedbacks...  *}
{section show=$cache_cleared.content}
    <div class="message-feedback">
        <h2>{'Content view cache was cleared'|i18n( 'design/admin/setup/cache' )} <span class="time">[{currentdate()|l10n( shortdatetime )}]</span></h2>
    </div>
{/section}

{section show=$cache_cleared.all}
    <div class="message-feedback">
        <h2>{'All caches were cleared'|i18n( 'design/admin/setup/cache' )} <span class="time">[{currentdate()|l10n( shortdatetime )}]</span></h2>
    </div>
{/section}

{section show=$cache_cleared.ini}
    <div class="message-feedback">
        <h2>{'Ini file cache was cleared'|i18n( 'design/admin/setup/cache' )} <span class="time">[{currentdate()|l10n( shortdatetime )}]</span></h2>
    </div>
{/section}

{section show=$cache_cleared.template}
    <div class="message-feedback">
        <h2>{'Template cache was cleared'|i18n( 'design/admin/setup/cache' )} <span class="time">[{currentdate()|l10n( shortdatetime )}]</span></h2>
    </div>
{/section}

{section show=$cache_cleared.list}
    <div class="message-feedback">
        <h2>{'Cache was cleared'|i18n( 'design/admin/setup/cache' )} <span class="time">[{currentdate()|l10n( shortdatetime )}]</span></h2>
        <ul>
        {section name=Cache loop=$cache_cleared.list}
             <li>{"%name was cleared"|i18n('design/admin/setup/cache', '', hash( '%name', $:item.name ) )}</li>
            {* {delimiter}<br/>{/delimiter} *}
        {/section}
        </ul>
    </div>
{/section}

<form name="clearcacheform" method="post" action={"/setup/cache/"|ezurl}>

{* Clear caches... *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Clear caches'|i18n( 'design/admin/setup/cache' )}</h1>
{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">

<tr class="bglight"><td>{'Template overrides and compiled templates'|i18n( 'design/admin/setup/cache' )}:</td><td>
<input class="button" type="submit" name="ClearTemplateCacheButton" value="{'Clear template caches'|i18n( 'design/admin/setup/cache' )}" />
</tr></td>
<tr class="bgdark"><td>{'Content views and template blocks'|i18n( 'design/admin/setup/cache' )}:</td><td>
<input class="button" type="submit" name="ClearContentCacheButton" value="{'Clear content caches'|i18n( 'design/admin/setup/cache' )}" />
</tr></td>
<tr class="bglight"><td>{'Configuration (ini) caches:'|i18n( 'design/admin/setup/cache' )}</td><td>
<input class="button" type="submit" name="ClearINICacheButton" value="{'Clear ini caches'|i18n( 'design/admin/setup/cache' )}" />
</tr></td>
<tr class="bgdark"><td>{'Everything:'|i18n( 'design/admin/setup/cache' )}</td><td>
<input class="button" type="submit" name="ClearAllCacheButton" value="{'Clear all caches'|i18n( 'design/admin/setup/cache' )}" />
</tr></td>
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>


{* Cache overview table *}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Cache overview'|i18n( 'design/admin/setup/cache' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="Invert selection." onclick="ezjs_toggleCheckboxes( document.clearcacheform, 'CacheList[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/content/trash' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/setup/cache' )}</th>
    <th>{'Path'|i18n( 'design/admin/setup/cache' )}</th>
</tr>
{section var=Caches loop=$cache_list sequence=array( bglight, bgdark )}

{* Checkbox *}
<tr class="{$Caches.sequence}">
{section show=$cache_enabled.list[$Caches.item.id]}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" /></td>
{section-else}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" disabled="disabled" /></td>
{/section}

{* Name *}
<td>{$Caches.item.name}&nbsp;</td>

{* Path *}
<td>{$Caches.item.path}&nbsp;</td>

</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="ClearCacheButton" value="{'Clear selected'|i18n( 'design/admin/setup/cache' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
