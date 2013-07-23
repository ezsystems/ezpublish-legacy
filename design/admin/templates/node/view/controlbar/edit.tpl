{* Edit button. *}
{def $can_create_languages = $node.object.can_create_languages
     $languages            = fetch( 'content', 'prioritized_languages' )}
{if $node.can_edit}
    {if and(eq( $languages|count, 1 ), is_set( $languages[0] ) )}
            <input name="ContentObjectLanguageCode" value="{$languages[0].locale}" type="hidden" />
    {else}
            <select name="ContentObjectLanguageCode">
            {foreach $node.object.can_edit_languages as $language}
                       <option value="{$language.locale}"{if $language.locale|eq($node.object.current_language)} selected="selected"{/if}>{$language.name|wash}</option>
            {/foreach}
            {if gt( $can_create_languages|count, 0 )}
                <option value="">{'New translation'|i18n( 'design/admin/node/view/full')}</option>
            {/if}
            </select>
    {/if}
    <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit the contents of this item.'|i18n( 'design/admin/node/view/full' )}" />
{else}
    <select name="ContentObjectLanguageCode" disabled="disabled">
        <option value="">{'Not available'|i18n( 'design/admin/node/view/full')}</option>
    </select>
    <input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
{/if}
{undef $can_create_languages}