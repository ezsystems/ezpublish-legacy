//
// main.cpp for ezlupdate
//
// This file is based on main.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2000 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <10-Dec-2002 18:46:17 gl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

#include <qstringlist.h>
#include <qregexp.h>
#include <qdir.h>
// #include <qfile.h>
// #include <qtextstream.h>

#include <errno.h>

#include <metatranslator.h>


// used in eZ Publish mode
void traverse( const QDir &dir, MetaTranslator &fetchedTor, bool assumeUTF8 = false );

// defined in fetchtr_php.cpp and fetchtr_tpl.cpp
extern void fetchtr_php( QFileInfo *fi, MetaTranslator *tor, bool mustExist );
extern void fetchtr_tpl( QFileInfo *fi, MetaTranslator *tor, bool mustExist, bool assumeUTF8 = false );

// defined in merge.cpp
extern void merge( MetaTranslator *tor, const MetaTranslator *virginTor, bool verbose );

static int verbose = 0;
static QString version = "3.8.0pre"; // eZ Publish version plus local version
static QStringList dirs;          // Additional scan directories
static bool extension = false;    // Extension mode
static QDir extension_dir;        // Extension directory
static QRegExp localeRE( "^[a-z]{3}-[A-Z]{2}$" );
static bool untranslated = false;    // Untranslated translation is off by default

static void printUsage()
{
    qWarning( "Creates or updates eZ Publish translations.\n"
              "Usage: ezlupdate [OPTION]... LANGUAGE\n\n"
              "Options:\n"
              "    -h, --help                Display this information and exit\n"
              "    -e, --extension EXT       Extension mode. Scans extension EXT instead of\n"
              "                              kernel, lib and design\n"
              "    -d, --dirs DIR [DIR]...   Directories to scan in addition to kernel, lib\n"
              "                              and designs\n"
              "    -u, --untranslated        Create/update the untranslated file as well\n"
              "    -no, --noobsolete         Drop all obsolete strings\n"
              "    --utf8                    Assume UTF8 when the encoding is uncertain\n"
              "    -v, --verbose             Explain what is being done\n"
              "    -vv                       Really explain what is being done\n"
              "    --version                 Display the version of ezlupdate and exit\n" );
}

int main( int argc, char **argv )
{
    // If no arguments, print help and exit
    if ( argc < 2 )
    {
        printUsage();
        return 0;
    }

    // Argument handling
    bool noObsolete = false;
    bool assumeUTF8 = false;
    QStringList languages;
    for ( int i = 1; i < argc; i++ )
    {
        if ( qstrcmp( argv[i], "--help" ) == 0 ||
             qstrcmp( argv[i], "-h" ) == 0 )
        {
            printUsage();
            return 0;
        }
        else if ( qstrcmp( argv[i], "--untranslated" ) == 0 ||
                  qstrcmp( argv[i], "-u" ) == 0 )
        {
            untranslated = true;
        }
        else if ( qstrcmp( argv[i], "--extension" ) == 0 ||
                  qstrcmp( argv[i], "-e" ) == 0 )
        {
            if ( i < argc - 1 )
            {
                i++;
                QString arg( argv[i] );
                extension_dir.setPath( arg );
                if ( !arg.startsWith( "-" ) && extension_dir.exists() )
                {
                    qWarning( "Extension mode, directory: " + arg );
                    extension = true;
                }
                else
                {
                    qFatal( "ERROR: Directory does not exist: " + arg );
                }
            }
            else
            {
                qFatal( "ERROR: Extension directory missing" );
            }
        }
        else if ( qstrcmp( argv[i], "--dirs" ) == 0 ||
                  qstrcmp( argv[i], "-d" ) == 0 )
        {
            ++i;
            while ( i < argc )
            {
                QString arg( argv[i] );
                if ( arg.startsWith( "-" ) )
                {
                    break;
                }
                QDir dir( arg );
                if ( dir.exists() )
                {
                    qWarning( "Added scan directory: " + arg );
                    dirs.append( arg );
                }
                i++;
            }
            continue;
        }
        else if ( qstrcmp( argv[i], "--noobsolete" ) == 0 ||
                  qstrcmp( argv[i], "-no" ) == 0 )
        {
            noObsolete = true;
            continue;
        }
        else if ( qstrcmp( argv[i], "--utf8" ) == 0 )
        {
            assumeUTF8 = true;
            continue;
        }
        else if ( qstrcmp( argv[i], "--verbose" ) == 0 ||
                  qstrcmp( argv[i], "-v" ) == 0 )
        {
            verbose = 1;
            continue;
        }
        else if ( qstrcmp( argv[i], "-vv" ) == 0 )
        {
            verbose = 2;
            continue;
        }
        else if ( qstrcmp( argv[i], "--version" ) == 0 )
        {
            qWarning( QString( "ezlupdate version %1-%2" ).arg( QT_VERSION_STR ).arg( version ) );
            return 0;
        }
        else
        {
            QString language = argv[i];
            if ( localeRE.indexIn( language ) == -1 )
            {
                qFatal( "ERROR - Locale should be on the form aaa-AA. Examples: eng-GB, nor-NO" );
            }
            else
                languages.append( language );
        }
    }

    if ( untranslated )
    {
        // Add the untranslated file to the list
        languages.append( "untranslated" );
    }

    if ( languages.count() == 0 )
    {
        qFatal( "ERROR - No languages defined, cannot continue." );
        return 1;
    }

    // Create/verify translation directory
    QDir tfdir( "share/translations" );
    if ( extension )
        tfdir.setPath( extension_dir.path() + QDir::separator() + "translations" );

    if ( !tfdir.exists() )
    {
        if ( QDir::current().mkdir( tfdir.path() ) )
        {
            qWarning( "eZ Publish translations directory created: " + tfdir.path() );
        }
        else
        {
            qFatal( "ERROR - eZ Publish translations directory could not be created: " + tfdir.path() );
        }
    }

    for ( QStringList::ConstIterator it = languages.begin(); it != languages.end(); ++it )
    {
        const QString &language = *it;
        QDir languageDir;
        languageDir.setPath( tfdir.path() + QDir::separator() + language );
        if ( !languageDir.exists() )
        {
            if ( QDir::current().mkdir( languageDir.path() ) )
            {
                qWarning( "eZ Publish translations directory created: " + languageDir.path() );
            }
            else
            {
                qFatal( "ERROR - eZ Publish translations directory could not be created: " + languageDir.path() );
            }
        }
    }

    // Start the real job
    QDir dir;
    QString currentPath = dir.absPath();
    MetaTranslator fetchedTor;
    if ( extension )
    {
        if ( verbose )
            qWarning( "Checking eZ Publish extension directory: '%s'", extension_dir.absPath().latin1() );
        dir.setCurrent( extension_dir.absPath() );
        traverse( dir.currentDirPath(), fetchedTor, assumeUTF8 );
    }
    else
    {
        if ( verbose )
            qWarning( "Checking eZ Publish directory: '%s'", dir.absPath().latin1() );
//        traverse( dir.path() + QDir::separator() + "kernel", fetchedTor, assumeUTF8 );
//        traverse( dir.path() + QDir::separator() + "lib", fetchedTor, assumeUTF8 );
//        traverse( dir.path() + QDir::separator() + "design", fetchedTor, assumeUTF8 );

        // Fix for bug in qt win free, only reads content of current directory
        dir.setCurrent( currentPath + "/kernel" );
        traverse( dir.currentDirPath(), fetchedTor, assumeUTF8 );
        dir.setCurrent( currentPath + "/lib" );
        traverse( dir.currentDirPath(), fetchedTor, assumeUTF8 );
        dir.setCurrent( currentPath + "/design" );
        traverse( dir.currentDirPath(), fetchedTor, assumeUTF8 );
    }

    // Additional directories
    dir.setCurrent( currentPath );
    for ( QStringList::Iterator it = dirs.begin(); it != dirs.end(); ++it )
    {
        dir.setCurrent( *it );
        traverse( dir.currentDirPath(), fetchedTor, assumeUTF8 );
    }

    // Cleanup
    dir.setCurrent( currentPath );

//         // Try to find codec from locale file
//         QString codec;
//         QFileInfo localefi( dir.absPath() + "/classes/locale/" + language + ".ini" );
//         if ( localefi.exists() )
//         {
//             QFile locale( localefi.filePath() );
//             if ( locale.open( IO_ReadOnly ) )
//             {
//                 QTextStream localeStream( &locale );
//                 QString line, liso = "LanguageISO";
//                 while ( !localeStream.atEnd() )
//                 {
//                     line = localeStream.readLine();
//                     if ( line.startsWith( liso ) )
//                     {
//                         codec = line.right( line.length() - liso.length() - 1 );
//                         break;
//                     }
//                 }
//                 locale.close();
//             }
//         }
//         if ( !codec.isNull() )
//         {
//             tor.setCodec( codec.latin1() );
//             if ( verbose )
//                 qWarning( "Setting codec for .ts file to: %s", codec.latin1() );
//         }
//         else
//             qWarning( "Warning: No codec found, setting codec for .ts file to default: iso-8859-1" );

    for ( QStringList::ConstIterator it = languages.begin(); it != languages.end(); ++it )
    {
        const QString &language = *it;
        MetaTranslator tor;

        QFileInfo fi( tfdir.path() + QDir::separator() + language + QDir::separator() + "translation.ts" );
        tor.load( fi.filePath() );
        if ( verbose )
            qWarning( "Updating '%s'", fi.filePath().latin1() );
        merge( &tor, &fetchedTor, verbose );
        if ( noObsolete )
            tor.stripObsoleteMessages();
        tor.stripEmptyContexts();

        if ( !tor.save( fi.filePath() ) )
            qWarning( "ezlupdate error: Cannot save '%s': %s", fi.filePath().latin1(), strerror( errno ) );
    }

    return 0;
}

/**!
   Recursively traverse an eZ Publish directory
*/
void traverse( const QDir &dir, MetaTranslator &fetchedTor, bool assumeUTF8 )
{
    if ( verbose )
        qWarning( "   Checking subdirectory '%s'", dir.path().latin1() );

    if ( !dir.exists() )
        return;

    const QFileInfoList list = dir.entryInfoList();
    QFileInfo fi;
    for ( int i = 0; i < list.size(); ++i )
    {
        fi = list.at( i );
        if ( fi.fileName().startsWith( "." ) )
        {
            // Do nothing
        }
        else if ( fi.isDir() )
        {
            QDir subdir = dir;
            subdir.setCurrent( subdir.path() + QDir::separator() + fi.fileName() );
            traverse( subdir.currentDirPath(), fetchedTor, assumeUTF8 );
            subdir.setCurrent( dir.path() );
        }
        else
        {
            if ( fi.fileName().endsWith( ".php", false ) )
            {
                if ( verbose > 1 )
                    qWarning( "      Checking '%s'", fi.fileName().latin1() );
                fetchtr_php( &fi, &fetchedTor, true );
            }
            else if ( fi.fileName().endsWith( ".tpl", false ) )
            {
                if ( verbose > 1 )
                    qWarning( "      Checking '%s'", fi.fileName().latin1() );
                fetchtr_tpl( &fi, &fetchedTor, true, assumeUTF8 );
            }
        }
    }
}
