/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   main.cpp
**
**   This file is part of Qt Linguist.
**
**   This file has been patched to include support for translating
**   eZ publish, see http://ez.no for more information.
**
**   See the file LICENSE included in the distribution for the usage
**   and distribution terms.
**
**   The file is provided AS IS with NO WARRANTY OF ANY KIND,
**   INCLUDING THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR
**   A PARTICULAR PURPOSE.
**
**********************************************************************/

#include <qfile.h>
#include <qstring.h>
#include <qstringlist.h>
#include <qtextstream.h>
#include <qdir.h>
#include <qregexp.h>

#include <errno.h>
#include <metatranslator.h>
#include <proparser.h>
#include <string.h>

// used in eZ publish mode
void traverse( const QDir &dir, MetaTranslator &fetchedTor );

static QString language;

// defined in fetchtr_ezpublish.cpp
extern void fetchtr_php( QFileInfo *fi, MetaTranslator *tor, bool mustExist );
extern void fetchtr_tpl( QFileInfo *fi, MetaTranslator *tor, bool mustExist );

// defined in fetchtr.cpp
extern void fetchtr_cpp( const char *fileName, MetaTranslator *tor,
                         const char *defaultContext, bool mustExist );
extern void fetchtr_ui( const char *fileName, MetaTranslator *tor,
                        const char *defaultContext, bool mustExist );

// defined in merge.cpp
extern void merge( MetaTranslator *tor, const MetaTranslator *virginTor, bool verbose );

typedef QValueList<MetaTranslatorMessage> TML;

static void printUsage()
{
    qWarning( "Usage: lupdate [options] file.pro...\n"
	      "Options:\n"
	      "    -help | -h\n"
          "           Display this information and exits\n"
	      "    -noobsolete | -no\n"
	      "           Drop all obsolete strings\n"
	      "    -verbose | -v | -vv\n"
	      "           Explain what is being done\n"
	      "    -version\n"
	      "           Display the version of lupdate and exits\n"
	      "    -ezpublish | -e [language] [publish_dir]\n"
	      "           Translate eZ publish from en_GB to [language], in [publish_dir].\n"
	      "           Will use current directory if [publish_dir] is not supplied.\n" );
}

static int verbose = 0;

int main( int argc, char **argv )
{
    bool noObsolete = false;
    bool metSomething = false;
    int numProFiles = 0;
    bool ezpublish = false;
    QString ezdirectory;
    bool newTSfile = true;;

    // Argument handling
    for ( int i = 1; i < argc; i++ )
    {
        if ( qstrcmp( argv[i], "-help" ) == 0 ||
             qstrcmp( argv[i], "-h" ) == 0 )
        {
            printUsage();
            return 0;
        }
        else if ( qstrcmp( argv[i], "-noobsolete" ) == 0 ||
                  qstrcmp( argv[i], "-no" ) == 0 )
        {
            noObsolete = true;
            continue;
        }
        else if ( qstrcmp( argv[i], "-verbose" ) == 0 ||
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
        else if ( qstrcmp( argv[i], "-version" ) == 0 )
        {
            qWarning( QString( "lupdate version %1 with eZ publish patch %2-nextgen-1" )
                      .arg( QT_VERSION_STR ).arg( QT_VERSION_STR ) );
            return 0;
        }
        else if ( qstrcmp( argv[i], "-ezpublish" ) == 0 ||
                  qstrcmp( argv[i], "-e" ) == 0 )
        {
            ezpublish = true;
            if ( i+1 < argc )
            {
                i++;
                language = argv[i];
                if ( i+1 < argc ) {
                    QString arg( argv[i+1] );
                    if ( !arg.startsWith( "-" ) )
                    {
                        i++;
                        ezdirectory = arg;
                    }
                }
            }
            else
            {
                printUsage();
                return 1;
            }
        }


        // Traditional Qt translation mode
        if ( !ezpublish )
        {
            numProFiles++;
            QFile f( argv[i] );
            if ( !f.open(IO_ReadOnly) )
            {
                qWarning( "lupdate error: Cannot open project file '%s': %s",
                          argv[i], strerror(errno) );
                return 1;
            }

            QTextStream t( &f );
            QString fullText = t.read();
            f.close();

            MetaTranslator fetchedTor;
            QString defaultContext = "@default";
            QCString codec;
            QStringList translatorFiles;
            QStringList::Iterator tf;

            QMap<QString, QString> tagMap = proFileTagMap( fullText );
            QMap<QString, QString>::Iterator it;

            for ( it = tagMap.begin(); it != tagMap.end(); ++it )
            {
                QStringList toks = QStringList::split( QChar(' '), it.data() );
                QStringList::Iterator t;

                for ( t = toks.begin(); t != toks.end(); ++t )
                {
                    if ( it.key() == QString("HEADERS") ||
                         it.key() == QString("SOURCES") )
                    {
                        fetchtr_cpp( *t, &fetchedTor, defaultContext, true );
                        metSomething = true;
                    }
                    else if ( it.key() == QString("INTERFACES") ||
                              it.key() == QString("FORMS") )
                    {
                        fetchtr_ui( *t, &fetchedTor, defaultContext, true );
                        fetchtr_cpp( *t + QString(".h"), &fetchedTor,
                                     defaultContext, false );
                        metSomething = true;
                    }
                    else if ( it.key() == QString("TRANSLATIONS") )
                    {
                        translatorFiles.append( *t );
                        metSomething = true;
                    }
                    else if ( it.key() == QString("CODEC") )
                    {
                        codec = (*t).latin1();
                    }
                }
            }

            for ( tf = translatorFiles.begin(); tf != translatorFiles.end(); ++tf )
            {
                MetaTranslator tor;
                tor.load( *tf );
                if ( !codec.isEmpty() )
                    tor.setCodec( codec );
                if ( verbose )
                    qWarning( "Updating '%s'...", (*tf).latin1() );
                merge( &tor, &fetchedTor, verbose );
                if ( noObsolete )
                    tor.stripObsoleteMessages();
                tor.stripEmptyContexts();
                if ( !tor.save(*tf) )
                    qWarning( "lupdate error: Cannot save '%s': %s", (*tf).latin1(),
                              strerror(errno) );
            }
            if ( !metSomething )
            {
                qWarning( "lupdate warning: File '%s' does not look like a project"
                          " file", argv[i] );
            }
            else if ( translatorFiles.isEmpty() )
            {
                qWarning( "lupdate warning: Met no 'TRANSLATIONS' entry in project"
                          " file '%s'", argv[i] );
            }
        }
    }

    if ( numProFiles == 0 && !ezpublish )
    {
        printUsage();
        return 1;
    }


    // eZ publish translation mode
    if ( ezpublish )
    {
        if ( !ezdirectory.isNull() )
        {
            if ( !QDir::setCurrent( ezdirectory ) || !QDir::current().exists() )
            {
                qWarning( "lupdate error: eZ publish directory '" +
                          ezdirectory + "'               does not exist." );
                return 1;
            }
        }

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

        QFileInfo fi( tfdir.path() + "/translation.ts" );
        if ( fi.exists() )
            newTSfile = false;

        MetaTranslator fetchedTor;
        QDir dir;
        if ( verbose )
            qWarning( "Checking eZ publish directory: '%s'", dir.absPath().latin1() );
        traverse( dir.path() + QDir::separator() + "kernel", fetchedTor );
        traverse( dir.path() + QDir::separator() + "lib", fetchedTor );
//         traverse( dir.path() + QDir::separator() + "design", fetchedTor );

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

	    tor.load( fi.filePath() );
	    if ( verbose )
            qWarning( "Updating '%s'", fi.filePath().latin1() );
	    merge( &tor, &fetchedTor, verbose );
	    if ( noObsolete )
            tor.stripObsoleteMessages();
	    tor.stripEmptyContexts();

	    if ( !tor.save( fi.filePath() ) )
            qWarning( "lupdate error: Cannot save '%s': %s", fi.filePath().latin1(), strerror( errno ) );
	}

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
