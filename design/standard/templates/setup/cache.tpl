<form method="post" action={"/setup/cache/"|ezurl}>

<h1>{"Cache admin"|i18n("design/standard/setup")}</h1>

{section show=$cache_cleared.content}
<div class="feedback">
{"Content view cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

{section show=$cache_cleared.all}
<div class="feedback">
{"All caches were cleared."}
</div>
{/section}

{section show=$cache_cleared.ini}
<div class="feedback">
{"Ini file cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

{section show=$cache_cleared.template}
<div class="feedback">
{"Template cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

{section show=$cache_cleared.list}
<div class="feedback">
{section name=Cache loop=$cache_cleared.list}
{$:item.name} was cleared
{delimiter}<br/>{/delimiter}
{/section}
</div>
{/section}

<div class="objectheader">
<h2>{'Cache collections'}</h2>
</div>

<div class="object">
    <p>{'Click a button to clear a collection of caches.'}</p>


    <table>
    <tr>
        <td><p>{'All caches.'}</p></dt>
        <td><div class="buttonblock">
        {section show=$cache_enabled.all}
        <input type="submit" name="ClearAllCacheButton" value="{'All caches'}" />
        {section-else}
            <p>{'All caches are disabled'}</p>
        {/section}
    </div></td>
    </tr>

    <tr>
        <td><p>{'Content views and template blocks.'}</p></td>
        <td>
        {section show=$cache_enabled.content}
        <input type="submit" name="ClearContentCacheButton" value="{'Content caches'}" />
        {section-else}
            <p>{'Content caches is disabled'}</p>
        {/section}
        </td>
    </tr>

    <tr>
        <td><p>{'Template overrides and template compiling.'}</p></td>
        <td>
        {section show=$cache_enabled.template}
        <input type="submit" name="ClearTemplateCacheButton" value="{'Template caches'}" />
        {section-else}
            <p>{'Template caches are disabled'}</p>
        {/section}
        </td>
    </tr>

    <tr>
        <td><p>{'INI caches.'}</p></td>
        <td>
        {section show=$cache_enabled.ini}
        <input type="submit" name="ClearINICacheButton" value="{'INI caches'}" />
        {section-else}
            <p>{'INI cache is disabled'}</p>
        {/section}
        </td>
    </tr>

    </table>

</div>

<table class="list" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <th width="50%">{'Name'}</th>
    <th width="50%">{'Path'}</th>
    <th width="1">{'Selection'}</th>
</tr>
{section name=Cache loop=$cache_list sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td>{$:item.name}</td>
    <td>{$:item.path}</td>
{section show=$cache_enabled.list[$:item.id]}
    <td align="right"><input type="checkbox" name="CacheList[]" value="{$:item.id}" /></td>
{section-else}
    <td align="right">{'Disabled'}</td>
{/section}
</tr>
{/section}
</table>

<div class="buttonblock" align="right">
    <input type="submit" name="ClearCacheButton" value="{'Clear selected'}" />
</div>

{*
<h2>Content view cache</h2>
{section show=$view_cache_enabled}
<p>
{"View cache is enabled."|i18n("design/standard/setup")}
</p>
{section-else}
<p>
{"View cache is disabled."|i18n("design/standard/setup")}
</p>
{/section}

<input type="submit" name="ClearContentCacheButton" value="{"Clear"|i18n("design/standard/setup")}" />

<h2>{"Ini cache"|i18n("design/standard/setup")}</h2>
<p>
{"Ini cache is always enabled."|i18n("design/standard/setup")}
</p>

<input type="submit" name="ClearINICacheButton" value="{"Clear"|i18n("design/standard/setup")}" />

<h2>{"Template cache"|i18n("design/standard/setup")}</h2>

<input type="submit" name="ClearTemplateCacheButton" value="{"Clear"|i18n("design/standard/setup")}" />

*}

</form>
