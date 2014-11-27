{* Redirection hidden inputs, based on session variables, themselves based on POST data *}
{if ezhttp_hasvariable( 'RedirectURIAfterPublish', 'session' )}
    {def $redirect_uri_after_publish=ezhttp( 'RedirectURIAfterPublish', 'session' )}
{else}
    {if ezhttp_hasvariable( 'LastAccessesURI', 'session' )}
        {def $redirect_uri_after_publish=ezhttp( 'LastAccessesURI', 'session' )}
    {/if}
{/if}

{if ezhttp_hasvariable( 'RedirectIfDiscarded', 'session' )}
    {def $redirect_if_discarded=ezhttp( 'RedirectIfDiscarded', 'session' )}
{else}
    {if ezhttp_hasvariable( 'LastAccessesURI', 'session' )}
        {def $redirect_if_discarded=ezhttp( 'LastAccessesURI', 'session' )}
    {/if}
{/if}

{if is_set( $redirect_uri_after_publish) }
    <input type="hidden" name="RedirectURIAfterPublish" value="{$redirect_uri_after_publish}" />
{/if}
{if is_set( $redirect_if_discarded ) }
    <input type="hidden" name="RedirectIfDiscarded" value="{$redirect_if_discarded}" />
{/if}
