{*?template charset=latin1?*}
{default enable_glossary=true() enable_help=true()}

<link rel="home" href={"/"|ezurl} title="{'%1 front page'|i18n('design/standard/layout',,array($site.title))}" />
<link rel="index" href={"/"|ezurl} />
<link rel="top"  href={"/"|ezurl} title="{$site_title}" />
<link rel="search" href={"content/advancedsearch"|ezurl} title="{'Search %1'|i18n('design/standard/layout',,array($site.title))}" />
<link rel="shortcut icon" href={"favicon.ico"|ezimage} type="image/x-icon" />

{section show=$enable_glossary}
<link rel="help" href={"manual"|ezurl} />
{/section}
{section show=$enable_glossary}
<link rel="glossary" href={"sdk"|ezurl} />
{/section}

{/default}