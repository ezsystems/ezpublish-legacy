{* Information window. *}
{section show=ezpreference( 'admin_navigation_information'  )}
    {include uri='design:information.tpl'}
{/section}

{* Locations window. *}
{section show=ezpreference( 'admin_navigation_locations'  )}
    {include uri='design:locations.tpl'}
{/section}

{* Related objects window. *}
{section show=ezpreference( 'admin_navigation_relations'  )}
    {include uri='design:related_objects.tpl'}
{/section}

{* Member of roles window. *}
{section show=ezpreference( 'admin_navigation_roles'  )}
    {include uri='design:roles.tpl'}
{/section}

{* Policy list window. *}
{section show=ezpreference( 'admin_navigation_policies'  )}
    {include uri='design:policies.tpl'}
{/section}

{* Children window.*}
{section show=$node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{section-else}
    {include uri='design:no_children.tpl'}
{/section}
