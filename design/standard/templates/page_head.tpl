{*?template charset=latin1?*}
{default enable_glossary=true() enable_help=true()}

{set-block variable=site_title}
{$site.title} - {section name=Path loop=$module_result.path}{$:item.text}{delimiter} / {/delimiter}{/section}
{/set-block}

    <title>{$site_title}</title>

    {* check if we need a http-equiv refresh *}
    {section show=$site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/section}

    {section name=HTTP loop=$site.http_equiv}
    <meta http-equiv="{$HTTP:key}" content="{$HTTP:item}" />

    {/section}

    {section name=meta loop=$site.meta}
    <meta name="{$meta:key}" content="{$meta:item}" />

    {/section}

    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ publish" />

{include uri="design:link.tpl" enable_glossary=$enable_glossary enable_help=$enable_help}
{/default}
