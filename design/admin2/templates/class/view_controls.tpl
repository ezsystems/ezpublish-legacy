<div class="block">
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc">
        <form action={concat( '/class/edit/', $class.id )|ezurl} method="post">

            {def $languages=$class.prioritized_languages
                 $availableLanguages = fetch( 'content', 'prioritized_languages' )}
            {if and( eq( $availableLanguages|count, 1 ), eq( $languages|count, 1 ), is_set( $languages[$availableLanguages[0].locale] ) )}
                <input type="hidden" name="EditLanguage" value="{$availableLanguages[0].locale|wash()}" />
            {else}
                <select name="EditLanguage" title="{'Use this menu to select the language you want to use for editing then click the "Edit" button.'|i18n( 'design/admin/class/view' )|wash()}">
                    {foreach $languages as $language}
                        <option value="{$language.locale|wash()}">{$language.name|wash()}</option>
                    {/foreach}
                    {if gt( $class.can_create_languages|count, 0 )}
                        <option value="">{'Another language'|i18n( 'design/admin/class/view')}</option>
                    {/if}
                </select>
            {/if}
            {undef $languages $availableLanguages}
            <input class="button" type="submit" name="_DefaultButton" value="{'Edit'|i18n( 'design/admin/class/view' )}" title="{'Edit this class.'|i18n( 'design/admin/class/view' )}" />
            {* <input class="button" type="submit" name="_DefaultButton" value="{'Remove'|i18n( 'design/admin/class/view' )}" /> *}
        </form>
{* DESIGN: Control bar END *}</div>
</div>
</div>