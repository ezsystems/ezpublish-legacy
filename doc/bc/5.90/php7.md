# PHP 7 support


For the [2017.10 release](https://github.com/ezsystems/ezpublish-legacy/releases/tag/v2017.10.0),
eZ Publish recived changes to switch to PHP 5 style constuctors all over the code base.

Reason is to reach full PHP 7 support by avoiding deprecation warnings for still using PHP 4
style constructors.

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

Further reading: http://php.net/manual/en/language.oop5.decon.phpi


Note: You should also increase requriment for ezplublish-legacy once the above changes are done like following example:
```diff
diff --git a/composer.json b/composer.json
index de225eb1..d0389c6d 100644
--- a/composer.json
+++ b/composer.json
@@ -11,7 +11,8 @@
     ],
     "minimum-stability": "dev",
     "require": {
-        "ezsystems/ezpublish-legacy-installer": "*"
+        "ezsystems/ezpublish-legacy-installer": "*",
+        "ezsystems/ezpublish-legacy": ">=2017.10"
     },
     "extra": {
         "ezpublish-legacy-extension-name": "ezfind"
```

