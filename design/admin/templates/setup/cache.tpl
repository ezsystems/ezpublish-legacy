{* Feedbacks. *}
{section show=$cache_cleared.content}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Content view cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.all}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'All caches were cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.ini}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Ini file cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.template}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Template cache was cleared'|i18n( 'design/admin/setup/cache' )}</h2>
    </div>
{/section}

{section show=$cache_cleared.list}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The following caches were cleared'|i18n( 'design/admin/setup/cache' )}:</h2>
        <ul>
        {section var=Caches loop=$cache_cleared.list}
            <li>{'%name was cleared'|i18n( 'design/admin/setup/cache',, hash( '%name', $Caches.item.name ) )}</li>
        {/section}
        </ul>
    </div>
{/section}




<form name="clearcacheform" method="post" action={"/setup/cache/"|ezurl}>

{* Clear caches window. *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Clear caches'|i18n( 'design/admin/setup/cache' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">

{* Template cache. *}
<tr class="bglight">
<td>{'Template overrides and compiled templates'|i18n( 'design/admin/setup/cache' )}:</td>
<td><input class="button" type="submit" name="ClearTemplateCacheButton" value="{'Clear template caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all the template override caches and the compiled templates. It may lead to weaker performance until the caches are up and running again.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

{* Content cache. *}
<tr class="bgdark">
<td>{'Content views and template blocks'|i18n( 'design/admin/setup/cache' )}:</td>
<td><input class="button" type="submit" name="ClearContentCacheButton" value="{'Clear content caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all caches that are related to either template views or cache blocks inside the pagelayout template. Use it if you have modified templates or if you have changed something inside a cache block.'|i18n( 'design/admin/setup/cache' )}"/></td>
</tr>

{* Configuration cahce. *}
<tr class="bglight">
<td>{'Configuration (ini) caches'|i18n( 'design/admin/setup/cache' )}:</td>
<td><input class="button" type="submit" name="ClearINICacheButton" value="{'Clear ini caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear all the configuration caches. Use it to force the system to re-read the configuration files if you have changed some settings.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

{* All caches. *}
<tr class="bgdark">
<td>{'Everything'|i18n( 'design/admin/setup/cache' )}:</td>
<td><input class="button" type="submit" name="ClearAllCacheButton" value="{'Clear all caches'|i18n( 'design/admin/setup/cache' )}" title="{'This operation will clear ALL the caches and may lead to long response times until the caches are up and running again.'|i18n( 'design/admin/setup/cache' )}" /></td>
</tr>

</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>




{* Cache overview window. *}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Fine-grained cache control'|i18n( 'design/admin/setup/cache' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/setup/cache' )}" onclick="ezjs_toggleCheckboxes( document.clearcacheform, 'CacheList[]' ); return false;" title="{'Invert selection.'|i18n( 'design/admin/setup/cache' )}" /></th>
    <th>{'Name'|i18n( 'design/admin/setup/cache' )}</th>
    <th>{'Path'|i18n( 'design/admin/setup/cache' )}</th>
</tr>
{section var=Caches loop=$cache_list sequence=array( bglight, bgdark )}

{* Checkbox *}
<tr class="{$Caches.sequence}">
{section show=$cache_enabled.list[$Caches.item.id]}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" title="{'Select the <%cache_name> for clearing.'|i18n( 'design/admin/setup/cache',, hash( '%cache_name', $Caches.item.name ) )|wash}" /></td>
{section-else}
<td><input type="checkbox" name="CacheList[]" value="{$Caches.item.id}" disabled="disabled" title="{'The <%cache_name> is disabled and thus it can not be marked for clearing.'|i18n( 'design/admin/setup/cache',, hash( '%cache_name', $Caches.item.name ) )|wash}" /></td>
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
<input class="button" type="submit" name="ClearCacheButton" value="{'Clear selected'|i18n( 'design/admin/setup/cache' )}" title="{'Clear the selected caches.'|i18n( 'design/admin/setup/cache' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
