<h1>Information on URL</h1>
{section show=$url_object.is_valid|not}
<div class="warning">
<p>{"The URL is not considered valid anymore."|i18n('design/standard/url')}</p>
<p>{"This means that the url is no longer available or has been moved."|i18n('design/standard/url')}</p>
</div>
{/section}
<p>{"The URL points to %1."|i18n('design/standard/url',,array(concat('<a href="',$url_object.url,'">',$url_object.url,"</a>")))}</p>

{section show=$url_object.modified|gt(0)}
<p>{"Last modified at %1"|i18n('design/standard/url',,array($url_object.modified|l10n(shortdatetime)))}</p>
{section-else}
<p>{"URL has no modification date"|i18n('design/standard/url')}</p>
{/section}

{section show=$url_object.last_checked|gt(0)}
<p>{"Last checked at %1"|i18n('design/standard/url',,array($url_object.last_checked|l10n(shortdatetime)))}</p>
{section-else}
<p>{"URL has not been checked"|i18n('design/standard/url')}</p>
{/section}
