# Default password length requirement changes 

For first v2019 release, the default password length requirement has been increased to 10 characters. Existing passwords that are shorter than this will continue to work, but when creating new passwords the new requirement must be fulfilled.

The default password of the Admin user is not changed. But you must of course change it before going live with a new project, and when you do, the new rule comes into effect.

The length of autogenerated passwords has also been increased, to 16 characters.

These defaults can be changed in site.ini [UserSettings], see MinPasswordLength and GeneratePasswordLength.