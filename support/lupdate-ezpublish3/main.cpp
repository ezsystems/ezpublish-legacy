//
// main.cpp for ezlupdate
//
// This file is based on main.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2000 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <10-Dec-2002 18:46:17 gl>
//
// Copyright (C) 1999-2002 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The GNU General Public License is also available online at:
//
// http://www.gnu.org/copyleft/gpl.html
//

#include <qstring.h>
#include <qdir.h>
// #include <qfile.h>
// #include <qtextstream.h>

#include <metatranslator.h>


// used in eZ publish mode
void traverse( const QDir &dir, MetaTranslator &fetchedTor );

// defined in fetchtr_php.cpp and fetchtr_tpl.cpp
extern void fetchtr_php( QFileInfo *fi, MetaTranslator *tor, bool mustExist );
extern void fetchtr_tpl( QFileInfo *fi, MetaTranslator *tor, bool mustExist );

// defined in merge.cpp
extern void merge( MetaTranslator *tor, const MetaTranslator *virginTor, bool verbose );

static int verbose = 0;
static int version = 1;

static void printUsage()
{
    qWarning( QString( "ezlupdate version %1-%2\n" ).arg( QT_VERSION_STR ).arg( version ) +
              "Create or update an eZ publish 3 translation from eng-GB to [language]\n\n"
              "Usage: ezlupdate [options] [language]\n"
              "Options:\n"
              "    --help | -h\n"
              "           Display this information and exit\n"
              "    --noobsolete | -no\n"
              "           Drop all obsolete strings\n"
              "    --verbose | -v | -vv\n"
              "           Explain what is being done\n"
              "    --version\n"
              "           Display the version of ezlupdate and exit\n" );
}

int main( int argc, char **argv )
{
    if ( argc < 2 )
    {
        printUsage();
        return 0;
    }

    // Argument handling
    bool noObsolete = false;
    QString language;
    for ( int i = 1; i < argc; i++ )
    {
        if ( qstrcmp( argv[i], "--help" ) == 0 ||
             qstrcmp( argv[i], "-h" ) == 0 )
        {
            printUsage();
            return 0;
        }
        else if ( qstrcmp( argv[i], "--noobsolete" ) == 0 ||
                  qstrcmp( argv[i], "-no" ) == 0 )
        {
            noObsolete = true;
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
            language = argv[i];
        }
    }

    // Create/verify translation directory
    QDir tfdir( "share/translations" );
    if ( !tfdir.exists() )
    {
        if ( QDir::current().mkdir( tfdir.path() ) )
        {
            qWarning( "eZ publish translations directory created: " + tfdir.path() );
        }
        else
        {
            qWarning( "ERROR - "
                      "eZ publish translations directory could not be created: " + tfdir.path() );
            return 1;
        }
    }
    tfdir.setPath( tfdir.path() + QDir::separator() + language );
    if ( !tfdir.exists() )
    {
        if ( QDir::current().mkdir( tfdir.path() ) )
        {
            qWarning( "eZ publish translations directory created: " + tfdir.path() );
        }
        else
        {
            qWarning( "ERROR - "
                      "eZ publish translations directory could not be created: " + tfdir.path() );
            return 1;
        }
    }

    // Start the real job
    MetaTranslator fetchedTor;
    QDir dir;
    if ( verbose )
        qWarning( "Checking eZ publish directory: '%s'", dir.absPath().latin1() );
    traverse( dir.path() + QDir::separator() + "kernel", fetchedTor );
    traverse( dir.path() + QDir::separator() + "lib", fetchedTor );
    traverse( dir.path() + QDir::separator() + "design", fetchedTor );

    MetaTranslator tor;

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

    QFileInfo fi( tfdir.path() + "/translation.ts" );
    tor.load( fi.filePath() );
    if ( verbose )
        qWarning( "Updating '%s'", fi.filePath().latin1() );
    merge( &tor, &fetchedTor, verbose );
    if ( noObsolete )
        tor.stripObsoleteMessages();
    tor.stripEmptyContexts();

    if ( !tor.save( fi.filePath() ) )
        qWarning( "ezlupdate error: Cannot save '%s': %s", fi.filePath().latin1(), strerror( errno ) );

    return 0;
}

/**!
   Recursively traverse an eZ publish directory
*/
void traverse( const QDir &dir, MetaTranslator &fetchedTor )
{
    if ( verbose )
        qWarning( "   Checking subdirectory '%s'", dir.path().latin1() );

    const QFileInfoList *list = dir.entryInfoList();
    QFileInfoListIterator it( *list );
    QFileInfo *fi;
    while ( (fi = it.current()) )
    {
        if ( fi->fileName().startsWith( "." ) )
        {
            ++it;
            continue;
        }
        else if ( fi->isDir() )
        {
            QDir subdir = dir;
            if ( subdir.cd( fi->fileName() ) )
                traverse( subdir, fetchedTor );
        }
        else
        {
            if ( fi->fileName().endsWith( ".php" ) )
            {
                if ( verbose > 1 )
                    qWarning( "      Checking '%s'", fi->fileName().latin1() );
                fetchtr_php( fi, &fetchedTor, true );
            }
            else if ( fi->fileName().endsWith( ".tpl" ) )
            {
                if ( verbose > 1 )
                    qWarning( "      Checking '%s'", fi->fileName().latin1() );
                fetchtr_tpl( fi, &fetchedTor, true );
            }
        }
        ++it;
    }
}
