import os

conv = "../template_conversion/stable/conv"

files = """
extension/ezwebin/design/ezwebin/templates/pagelayout.tpl
extension/ezwebin/design/ezwebin/templates/link.tpl
extension/ezwebin/design/ezwebin/templates/menu/flat_top.tpl
extension/ezwebin/design/ezwebin/templates/menu/flat_left.tpl
extension/ezwebin/design/ezwebin/templates/parts/extra_info.tpl
extension/ezwebin/design/ezwebin/templates/parts/path.tpl
extension/ezwebin/design/ezwebin/templates/page_footer.tpl
design/standard/templates/page_head.tpl
design/standard/templates/setup/debug_toolbar.tpl
design/standard/templates/setup/clear_cache.tpl
design/standard/templates/setup/quick_settings.tpl
"""

list = files.split()

for l in list:
    dir = "new_templates/" + os.path.dirname(l)
    if not os.path.isdir(dir):
        os.makedirs(dir)

    os.system("%s -v %s %s" % (conv, l, "new_templates/" + l ) )

