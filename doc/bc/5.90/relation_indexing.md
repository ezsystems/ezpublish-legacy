# Relation search indexing changes 

For the 2018.06 release, eZ Publish changed the way relations are indexed. Previously the relation metadata was loaded recursively, meaning that relations of relations etc. were also included. This behavior was changed to only include Objects one level deep so only direct relations are taken into account.

The change was done to avoid various recursion problems when relations are circular. Also, the new behavior allows for better (more relevant) search results.

The affected kernel Datatypes are eZObjectRelationType and eZObjectRelationListType.

If you have your custom Datatype that stores relations to other Objects, you can make it behave similarly. Just add the following to your Datatype class:
```php
    public function isRelationType()
    {
        return true;
    }
```
