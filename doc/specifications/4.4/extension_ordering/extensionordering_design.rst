Extension ordering
==================

The order in which active extensions appear in
*site.ini/[ExtensionSettings]/ActiveExtensions* is important for the selection
of templates and, possibly, configuration settings. For this reason, users have
to care much about it so that a correct order is used with extensions that have
some awareness of others.

**Problem**: Today the admin interface does not provide a way to reorder them
whether manually or automatically and the good order is sometimes tricky to
determine when many extensions comes into account.

Scope
-----

By providing metadata about ordering we enable eZ Publish to correctly determine
the correct order. The current goal is not about dependency or requirement
checking, nor automatically activating an extension.

Metadata
--------

Metadata about extension ordering are optional. They are placed in a
*loading.php* file at the root directory of the extension and should be
structured the following way:

::

    extension/example/loading.php
    <?php
    return array(
        'after' => array(
            'extension1',
            'extension2',
        ),
        'before' => array(
            'extension3',
        )
    );

This will instruct that extension *example* needs to be loaded **after**
*extension1* and *extension2*, but **before** *extension3*.

Ordering algorithm
------------------

**Topological sorting** is the way to go, a directed graph is created with the
information extracted from metadata files. Continuing the example above, this
will result in the following graph:

::

    extension1
              \
               \
                ->example ---> extension3
               /
              /
    extension2

The resolution is done by:

1. Creating a list of extensions to load.

2. Creating an empty list which will contain the ordering.

3. Cycling on those which have no dependencies.

4. Add them to the ordered list.

5. Removing them from the graph and from the list of extensions to load.

6. a) Continuing starting at 3. until the list of extensions to load is empty.
   b) If the list of extensions with no dependencies is empty it means a cycle
      exists.

The algorithm is contained in the *ezpTopologicalSort* class which internally
uses the *ezpTopologicalSortNode* one to represent nodes of the graph.
