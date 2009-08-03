{if ezini( 'eZJSCore', 'LoadFromCDN', 'ezjscore.ini',,true() )|eq('enabled')}
    {def $externalScripts = ezini( 'eZJSCore', 'ExternalScripts', 'ezjscore.ini' )
         $enabledScripts = ezini( 'eZJSCore', 'EnabledScripts', 'ezjscore.ini' )|unique()}
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

    {ezscript( ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) )}
{else}
    {def $localScripts   = ezini( 'eZJSCore', 'LocaleScripts', 'ezjscore.ini' )
         $enabledScripts = ezini( 'eZJSCore', 'EnabledScripts', 'ezjscore.ini' )|unique()
         $scriptBasePath = ezini( 'eZJSCore', 'LocaleScriptBasePath', 'ezjscore.ini' )
         $javaScriptList = ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' )}
    {foreach $enabledScripts as $type}
        {set $javaScriptList = $javaScriptList|prepend( $localScripts[ $type ] )}
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