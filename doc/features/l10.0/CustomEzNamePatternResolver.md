Object name pattern and URL alias name pattern
=

Existing functionality
-
As an admin user, you can edit a class definition in the admin UI.
There are 2 text fields allowing you to specify:
* Object name pattern
* URL alias name pattern 

Typically, you specify an attribute of that class. The attribute value
is used to generate the object name (URL alias name). It is
simplifying the string in order to build valid object/URL alias names.

New feature
-
As an admin user, you can now specify a PHP class which
is responsible to generate the object/URL alias name.
While editing the content class in the admin uI, you can
specify that PHP class in the 2 text fields for
object/URL alias name pattern.

Here is an example value: {MyCustomEzNamePatternResolver}

The system will get an instance of your PHP class and calls
the `function resolveNamePattern`. You have to make sure that
the PHP class 'MyCustomEzNamePatternResolver' is a subclass of
'eZNamePatternResolver'. Best pratice is to put that class
in your eZ Publish extension under the directory 'classes'.
