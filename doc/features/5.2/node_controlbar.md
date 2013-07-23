# Node controlbar

Added possibility to add controls to a node's full view in the admin interface

## Previous behaviour

In order to add new controls to a node's full view in the admin interface, you would have to override the whole
`design/admin/templates/node/view/full.tpl` template, which meant that you wouldn't automatically receive updates
to the file coming with an update of eZ Publish.

## New behaviour

To add a new control (besides 'Edit', 'Move' and 'Remove') create a new override for the file `controlbar.ini` in your
admin siteaccess settings folder:

    <?php /* #?ini charset="utf-8"?

    [NodeControlbar]
    Controls[]=my_custom_control
    Controls[]=my_custom_image_control

    [NodeControlbar_my_custom_control]
    Template=my_custom_control.tpl
    AvailableForAllClasses=true
    AvailableForClasses[]

    [NodeControlbar_my_custom_image_control]
    Template=my_custom_image_control.tpl
    AvailableForClasses=false
    AvailableForClasses[]
    AvailableForClasses[]=image

    /* ?>

Place the new templates in the folder `extension/<my_extension>/design/admin/templates/node/controlbar`. You can take
the existing controls in `design/admin/templates/node/controlbar` as examples.