{*?template charset=latin1?*}
{default enable_glossary=true() enable_help=true()}

{set-block variable=site_title}
  {section show=is_set($module_result.title_path)}
{$site.title|wash} - {section name=Path loop=$module_result.title_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section}
  {section-else}
{$site.title|wash} - {section name=Path loop=$module_result.path}{$:item.text|wash}{delimiter} / {/delimiter}{/section}
  {/section}
{/set-block}

    <title>{$site_title}</title>

    {section show=and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
      {section name=ExtraData loop=$#Header:extra_data}
      {$:item}
      {/section}
    {/section}

    {* check if we need a http-equiv refresh *}
    {section show=$site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/section}

    {section name=HTTP loop=$site.http_equiv}
    <meta http-equiv="{$HTTP:key|wash}" content="{$HTTP:item|wash}" />

    {/section}

    {section name=meta loop=$site.meta}
    <meta name="{$meta:key|wash}" content="{$meta:item|wash}" />

    {/section}

    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ publish" />

{include uri="design:link.tpl" enable_glossary=$enable_glossary enable_help=$enable_help}
{/default}
