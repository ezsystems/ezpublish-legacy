<form method="post" action={"/setup/cache/"|ezurl}>

<h1>Cache admin</h1>

{section show=$view_cache_cleared}
<div class="feedback">
Content view cache was cleared.
</div>
{/section}

{section show=$ini_cache_cleared}
<div class="feedback">
Ini file cache was cleared.
</div>
{/section}

{section show=$template_cache_cleared}
<div class="feedback">
Template cache was cleared.
</div>
{/section}

<h2>Content view cache</h2>
{section show=$view_cache_enabled}
<p>
View cache is enabled.
</p>
{section-else}
<p>
View cache is disabled.
</p>
{/section}

<input type="submit" name="ClearContentCacheButton" value="Clear" />

<h2>Ini cache</h2>
<p>
Ini cache is always enabled.
</p>

<input type="submit" name="ClearINICacheButton" value="Clear" />

<h2>Template cache</h2>

<input type="submit" name="ClearTemplateCacheButton" value="Clear" />

</form>