<form method="post" action={"/setup/cache/"|ezurl}>

<h1>{"Cache admin"|i18n("design/standard/setup")}</h1>

{section show=$view_cache_cleared}
<div class="feedback">
{"Content view cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

{section show=$ini_cache_cleared}
<div class="feedback">
{"Ini file cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

{section show=$template_cache_cleared}
<div class="feedback">
{"Template cache was cleared."|i18n("design/standard/setup")}
</div>
{/section}

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

</form>