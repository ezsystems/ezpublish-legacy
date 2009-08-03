{default enable_help=true() enable_link=true()}

{let name=Path
     path=$module_result.path
     reverse_path=array()}
  {section show=is_set($module_result.title_path)}
    {set path=$module_result.title_path}
  {/section}
  {section loop=$:path}
    {set reverse_path=$:reverse_path|array_prepend($:item)}
  {/section}

{set-block scope=root variable=site_title}
{section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section} - {$site.title|wash}
{/set-block}

{/let}

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
    <meta name="generator" content="eZ Publish" />

{section show=$enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/section}

{if ezini( 'eZJSCore', 'LoadFromCDN', 'ezjscore.ini',,true() )|eq('enabled')}
    {def $externalScripts = ezini( 'eZJSCore', 'ExternalScripts', 'ezjscore.ini' )
         $enabledScripts  = ezini( 'eZJSCore', 'EnabledScripts', 'ezjscore.ini' )|unique()}
    {foreach $enabledScripts as $type}
        <script type="text/javascript" src="{$externalScripts[ $type ]}"></script>
        {if $type|eq('yui2')}
            <script type="text/javascript">
            var YUILoader =  new YAHOO.util.YUILoader({ldelim}
                loadOptional: true,
                combine: true
            {rdelim});
            </script>
        {elseif $type|eq('yui3')}
            <script type="text/javascript">
            var YUI3_config = {ldelim} modules: {ldelim}{rdelim} {rdelim};
            </script>
        {/if}
    {/foreach}
{else}
    {def $localScripts   = ezini( 'eZJSCore', 'LocaleScripts', 'ezjscore.ini' )
         $enabledScripts = ezini( 'eZJSCore', 'EnabledScripts', 'ezjscore.ini' )|unique()
         $scriptBasePath = ezini( 'eZJSCore', 'LocaleScriptBasePath', 'ezjscore.ini' )
         $javaScriptList = array()}
    {foreach $enabledScripts as $type}
        {set $javaScriptList = $javaScriptList|append( $localScripts[ $type ] )}
    {/foreach}

    {ezscript( $javaScriptList )}

    <script type="text/javascript">
    {if $enabledScripts|contains('yui2')}
    var YUILoader =  new YAHOO.util.YUILoader({ldelim}
        base: '{$scriptBasePath[ "yui2" ]|ezdesign( "no" )}',
        loadOptional: true
    {rdelim});
    {/if}
    {if $enabledScripts|contains('yui3')}
    var YUI3_config = {ldelim} 'base' : '{$scriptBasePath[ "yui3" ]|ezdesign( "no" )}', modules: {ldelim}{rdelim} {rdelim};
    {/if}
    </script>
{/if}
{/default}
