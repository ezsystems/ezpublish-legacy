# PHP 7 support

## PHP 7.0 support

For the [2017.10 release](https://github.com/ezsystems/ezpublish-legacy/releases/tag/v2017.10.0),
eZ Publish recived changes to switch to PHP 5 style constuctors all over the code base.

Reason is to reach full PHP 7.0 support by avoiding deprecation warnings for still using PHP 4
style constructors.

And while there are compatability functions kept around in most cases to avoid any fatal errors for you,
to avoid warnings you'll need to adapt too. 

Here is an example of how you might need to adapt for this change in your code:

```diff
diff --git a/classes/ezfindresultnode.php b/classes/ezfindresultnode.php
index fafca310..b6462159 100644
--- a/classes/ezfindresultnode.php
+++ b/classes/ezfindresultnode.php
@@ -12,7 +12,7 @@ class eZFindResultNode extends eZContentObjectTreeNode
     */
     function eZFindResultNode( $rows = array() )
     {
-        $this->eZContentObjectTreeNode( $rows );
+        parent::__construct( $rows );
         if ( isset( $rows['id'] ) )
         {
             $this->ContentObjectID = $rows['id'];
```

Other more common examples are classes extending `eZPersistentObject` or `eZDataType`.

You should also consider changing your own code to use PHP 5 style constructor while doing this,
in example above that would imply changing `function eZFindResultNode` to `function __construct`.

Further reading:
- http://php.net/manual/en/language.oop5.decon.php
- https://www.php.net/manual/en/migration70.incompatible.php
- https://www.php.net/manual/en/migration71.incompatible.php

## PHP 7.2 support

Starting with 2019.03 release, issues on PHP 7.2 and PHP 7.3 has been fixed, but in your own code you'll ideally also need to handle some of this.

Most notably is `Warn when counting non-countable types` added in PHP 7.2.

To hande this across all supported PHP versions, we intropduced use of [symfony/polyfill-php73](https://github.com/symfony/polyfill-php73) package, witch backports PHP 7.3's function [is_countable](https://www.php.net/is_countable).

Here is an example of changes you might need to do in your own code around this:

```diff
diff --git a/kernel/common/eztemplatedesignresource.php b/kernel/common/eztemplatedesignresource.php
index b0fc28faa9a..9b8ca2a8d94 100644
--- a/kernel/common/eztemplatedesignresource.php
+++ b/kernel/common/eztemplatedesignresource.php
@@ -86,7 +86,7 @@ function templateNodeTransformation( $functionName, &$node,
                 $matchCount = 0;
                 foreach ( $customMatchList as $customMatch )
                 {
-                    $matchConditionCount = count( $customMatch['conditions'] );
+                    $matchConditionCount = is_countable( $customMatch['conditions'] ) ? count( $customMatch['conditions'] ) : 0;
                     $code = '';
                     if ( $matchCount > 0 )
                     {
```

Further reading:
- https://www.php.net/manual/en/migration72.incompatible.php
- https://www.php.net/manual/en/migration73.incompatible.php
