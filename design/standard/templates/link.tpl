{*?template charset=latin1?*}
{default enable_glossary=true() enable_help=true()}

<link rel="Home" href={"/"|ezurl} title="{'%1 front page'|i18n('design/standard/layout',,array($site.title))}" />
<link rel="Index" href={"/"|ezurl} />
<link rel="Top"  href={"/"|ezurl} title="{$site_title}" />
<link rel="Search" href={"content/advancedsearch"|ezurl} title="{'Search %1'|i18n('design/standard/layout',,array($site.title))}" />
<link rel="Shortcut icon" href={"favicon.ico"|ezimage} type="image/x-icon" />
<link rel="Copyright" href={"/ezinfo/copyright"|ezurl} />
<link rel="Author" href={"/ezinfo/about"|ezurl} />

{section show=$enable_glossary}
<link rel="Help" href={"manual"|ezurl} />
{/section}
{section show=$enable_glossary}
<link rel="Glossary" href={"sdk"|ezurl} />
{/section}

<link rel=Alternate href={concat("layout/set/print/",$site.uri.tail)|ezurl} media=print title="Printable version" />

{/default}