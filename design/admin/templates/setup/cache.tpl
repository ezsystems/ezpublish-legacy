{* Feedbacks...  *}
{section show=$cache_cleared.content}
    <div class="feedback">
        {'Content view cache was cleared.'|i18n( 'design/admin/setup/cache' )}
    </div>
{/section}

{section show=$cache_cleared.all}
    <div class="feedback">
        {'All caches were cleared.'|i18n( 'design/admin/setup/cache' )}
    </div>
{/section}

{section show=$cache_cleared.ini}
    <div class="feedback">
        {'Ini file cache was cleared.'|i18n( 'design/admin/setup/cache' )}
    </div>
{/section}

{section show=$cache_cleared.template}
    <div class="feedback">
        {'Template cache was cleared.'|i18n( 'design/admin/setup/cache' )}
    </div>
{/section}

{section show=$cache_cleared.list}
    <div class="feedback">
        {section name=Cache loop=$cache_cleared.list}
            {"%name was cleared."|i18n('design/admin/setup/cache', '', hash( '%name', $:item.name ) )}
            {delimiter}<br/>{/delimiter}
        {/section}
    </div>
{/section}

<form method="post" action={"/setup/cache/"|ezurl}>

{* Clear caches... *}
<div class="context-block">
<h2 class="context-title">Clear caches</h2>
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
</div>


{* Cache overview table *}
<div class="context-block">
<h2 class="context-title">{'Cache overview'|i18n( 'design/admin/setup/cache' )}</h2>
<table class="list" cellspacing="0">
<tr>
    <th class="tight">&nbsp;</th>
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

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="ClearCacheButton" value="{'Clear selected'|i18n( 'design/admin/setup/cache' )}" />
</div>
</div>

</div>

</form>
