{* Details window. *}
{section show=ezpreference( 'admin_navigation_details' )}
    {include uri='design:details.tpl'}
{/section}

{* Translations window. *}
{section show=ezpreference( 'admin_navigation_translations' )}
    {include uri='design:translations.tpl'}
{/section}

{* Locations window. *}
{section show=ezpreference( 'admin_navigation_locations' )}
    {include uri='design:locations.tpl'}
{/section}

{* Relations window. *}
{section show=or( ezpreference( 'admin_navigation_relations' ),
                  and( is_set( $view_parameters.show_relations ), eq( $view_parameters.show_relations, 1 ) ) )}
    {include uri='design:relations.tpl'}
{/section}

{* States window. *}
{if eq( ezpreference( 'admin_navigation_states' ), 1)}
    {include uri='design:states.tpl'}
{/if}

{* Children window.*}
{section show=$node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{section-else}
    {include uri='design:no_children.tpl'}
{/section}
