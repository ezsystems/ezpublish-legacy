#!/bin/bash

export PATH=$PATH:/usr/local/php/bin/

# Make dir for packages
#rm -rf var/storage/packages/
rm -rf kernel/setup/packages
mkdir kernel/setup/packages

## Clean package
./ezpm.php -r packages create plain
./ezpm.php -r packages add plain design -n design plain
./ezpm.php -r packages add plain ini -r siteaccess -v plain -n user_siteaccess settings/siteaccess/plain/override.ini.append
./ezpm.php -r packages add plain ini -r siteaccess -v plain -n user_siteaccess settings/siteaccess/plain/site.ini.append
./ezpm.php -r packages add plain ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add plain ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add plain sql -d mysql kernel/sql/mysql/kernel_schema.sql
./ezpm.php -r packages add plain sql -d mysql kernel/sql/mysql/cleandata.sql
./ezpm.php -r packages add plain sql -d postgresql kernel/sql/postgresql/kernel_schema.sql
./ezpm.php -r packages add plain sql -d postgresql kernel/sql/postgresql/cleandata.sql
./ezpm.php -r packages add plain thumbnail plainthumbnail.png
./ezpm.php -r packages set plain summary "Plain"
./ezpm.php -r packages export plain -d kernel/setup/packages


## Intranet package
./ezpm.php -r packages create intranet
./ezpm.php -r packages add intranet design -n design intranet
./ezpm.php -r packages add intranet ini -r siteaccess -v intranet -n user_siteaccess settings/siteaccess/intranet/override.ini.append
./ezpm.php -r packages add intranet ini -r siteaccess -v intranet -n user_siteaccess settings/siteaccess/intranet/site.ini.append
./ezpm.php -r packages add intranet ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add intranet ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add intranet sql -d mysql packages/intranet.sql
./ezpm.php -r packages add intranet sql -d postgresql packages/intranet_postgresql.sql
./ezpm.php -r packages add intranet thumbnail intranetthumbnail.png
./ezpm.php -r packages set intranet summary "Intranet"
./ezpm.php -r packages export intranet -d kernel/setup/packages

## Forum package
./ezpm.php -r packages create forum
./ezpm.php -r packages add forum design -n design forum
./ezpm.php -r packages add forum ini -r siteaccess -v forum -n user_siteaccess settings/siteaccess/forum/override.ini.append
./ezpm.php -r packages add forum ini -r siteaccess -v forum -n user_siteaccess settings/siteaccess/forum/site.ini.append
./ezpm.php -r packages add forum ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add forum ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add forum sql -d mysql packages/forum.sql
./ezpm.php -r packages add forum sql -d postgresql packages/forum_postgresql.sql
./ezpm.php -r packages add forum thumbnail forumthumbnail.png
./ezpm.php -r packages set forum summary "Forum"
./ezpm.php -r packages export forum -d kernel/setup/packages


## Bookshop package
./ezpm.php -r packages create bookshop
./ezpm.php -r packages add bookshop design -n design bookshop
./ezpm.php -r packages add bookshop ini -r siteaccess -v bookshop -n user_siteaccess settings/siteaccess/bookshop/override.ini.append
./ezpm.php -r packages add bookshop ini -r siteaccess -v bookshop -n user_siteaccess settings/siteaccess/bookshop/site.ini.append
./ezpm.php -r packages add bookshop ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add bookshop ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add bookshop sql -d mysql packages/bookshop.sql
./ezpm.php -r packages add bookshop sql -d postgresql packages/bookshop_postgresql.sql
./ezpm.php -r packages add bookshop thumbnail bookshopthumbnail.png
./ezpm.php -r packages set bookshop summary "Bookshop"
./ezpm.php -r packages export bookshop -d kernel/setup/packages

## Gallery package
./ezpm.php -r packages create gallery
./ezpm.php -r packages add gallery design -n design gallery
./ezpm.php -r packages add gallery ini -r siteaccess -v gallery -n user_siteaccess settings/siteaccess/gallery/override.ini.append
./ezpm.php -r packages add gallery ini -r siteaccess -v gallery -n user_siteaccess settings/siteaccess/gallery/site.ini.append
./ezpm.php -r packages add gallery ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add gallery ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add gallery sql -d mysql packages/gallery.sql
./ezpm.php -r packages add gallery sql -d postgresql packages/gallery_postgresql.sql
./ezpm.php -r packages add gallery file var/storage/original/image/php2Kecsf.jpg 
./ezpm.php -r packages add gallery file var/storage/original/image/phpRFANTu.jpg
./ezpm.php -r packages add gallery file var/storage/original/image/phph8inGr.jpg
./ezpm.php -r packages add gallery file var/storage/original/image/phpUQzr4Z.jpg
./ezpm.php -r packages add gallery file var/storage/reference/image/php2Kecsf.jpg  
./ezpm.php -r packages add gallery file var/storage/reference/image/phpRFANTu.jpg
./ezpm.php -r packages add gallery file var/storage/reference/image/phph8inGr.jpg
./ezpm.php -r packages add gallery file var/storage/reference/image/phpUQzr4Z.jpg
./ezpm.php -r packages add gallery thumbnail gallerythumbnail.png
./ezpm.php -r packages set gallery summary "Gallery"
./ezpm.php -r packages export gallery -d kernel/setup/packages
 


## Mysite package
./ezpm.php -r packages create mysite
./ezpm.php -r packages add mysite design -n design mysite
./ezpm.php -r packages add mysite ini -r siteaccess -v mysite -n user_siteaccess settings/siteaccess/mysite/override.ini.append
./ezpm.php -r packages add mysite ini -r siteaccess -v mysite -n user_siteaccess settings/siteaccess/mysite/site.ini.append
./ezpm.php -r packages add mysite ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add mysite ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add mysite sql -d mysql packages/mysite.sql
./ezpm.php -r packages add mysite sql -d postgresql packages/mysite_postgresql.sql
./ezpm.php -r packages add mysite thumbnail mysitethumbnail.png
./ezpm.php -r packages set mysite summary "Personal"
./ezpm.php -r packages export mysite -d kernel/setup/packages


## News package
./ezpm.php -r packages create news
./ezpm.php -r packages add news design -n design news
./ezpm.php -r packages add news ini -r siteaccess -v news -n user_siteaccess settings/siteaccess/news/override.ini.append
./ezpm.php -r packages add news ini -r siteaccess -v news -n user_siteaccess settings/siteaccess/news/site.ini.append
./ezpm.php -r packages add news ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add news ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add news sql -d mysql packages/news.sql
./ezpm.php -r packages add news sql -d postgresql packages/news_postgresql.sql
./ezpm.php -r packages add news thumbnail newsthumbnail.png
./ezpm.php -r packages set news summary "News"
./ezpm.php -r packages export news -d kernel/setup/packages

## Corprate
./ezpm.php -r packages create corporate
./ezpm.php -r packages add corporate design -n design corporate
./ezpm.php -r packages add corporate ini -r siteaccess -v corporate -n user_siteaccess settings/siteaccess/corporate/override.ini.append
./ezpm.php -r packages add corporate ini -r siteaccess -v corporate -n user_siteaccess settings/siteaccess/corporate/site.ini.append
./ezpm.php -r packages add corporate ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/override.ini.append
./ezpm.php -r packages add corporate ini -r siteaccess -v admin -n admin_siteaccess settings/siteaccess/admin/site.ini.append
./ezpm.php -r packages add corporate sql -d mysql packages/corporate.sql
./ezpm.php -r packages add corporate sql -d postgresql packages/corporate_postgresql.sql
./ezpm.php -r packages add corporate thumbnail corporatethumbnail.png
./ezpm.php -r packages set corporate summary "Corporate"
./ezpm.php -r packages export corporate -d kernel/setup/packages



ls -la kernel/setup/packages

./bin/shell/makedist.sh