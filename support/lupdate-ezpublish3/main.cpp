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
void checkFile( QFileInfo *origfi, MetaTranslator &fetchedTor );
void checkLocale( MetaTranslator &fetchedTor );
void importTranslation( MetaTranslator &tor, const QString &filePath );
const QString findValue( const QString &findkey, QStringList &lines );
static const QString ezoriglanguage = "en_GB";
static QString ezlanguage;
static const QString keyprefix = "Key: ";
static const QRegExp keyValueRE( "[^=]+=.*" );
//static const QRegExp keyValueRE( "[0-9A-Za-z_]+=.*" );
// defined in fetchtr_ezpublish.cpp
extern void fetchtr_ezpublish( QFileInfo *fi, MetaTranslator *tor, bool mustExist );

// defined in fetchtr.cpp
extern void fetchtr_cpp( const char *fileName, MetaTranslator *tor,
			 const char *defaultContext, bool mustExist );
extern void fetchtr_ui( const char *fileName, MetaTranslator *tor,
			const char *defaultContext, bool mustExist );

// defined in merge.cpp
extern void merge( MetaTranslator *tor, const MetaTranslator *virginTor,
		   bool verbose );

typedef QValueList<MetaTranslatorMessage> TML;

static void printUsage()
{
    qWarning( "Usage: lupdate [options] file.pro...\n"
	      "Options:\n"
	      "    -help | -h\n"
          "           Display this information and exits\n"
	      "    -noobsolete | -no\n"
	      "           Drop all obsolete strings\n"
	      "    -verbose | -V\n"
	      "           Explain what is being done\n"
	      "    -version | -v\n"
	      "           Display the version of lupdate and exits\n"
	      "    -ezpublish | -e [language] [publish_dir]\n"
	      "           Translate eZ publish from en_GB to [language], in [publish_dir].\n"
	      "           Will use current directory if [publish_dir] is not supplied.\n" );
}

static bool verbose = FALSE;

int main( int argc, char **argv )
{
    bool noObsolete = FALSE;
    bool metSomething = FALSE;
    int numProFiles = 0;
    bool ezpublish = FALSE;
    QString ezdirectory;
    bool newTSfile = TRUE;;

    // Argument handling
    for ( int i = 1; i < argc; i++ )
    {
        if ( qstrcmp(argv[i], "-help") == 0 || qstrcmp(argv[i], "-h") == 0 )
        {
            printUsage();
            return 0;
        }
        else if ( qstrcmp(argv[i], "-noobsolete") == 0 || qstrcmp(argv[i], "-no") == 0 )
        {
            noObsolete = TRUE;
            continue;
        }
        else if ( qstrcmp(argv[i], "-verbose") == 0 || qstrcmp(argv[i], "-V") == 0 )
        {
            verbose = TRUE;
            continue;
        }
        else if ( qstrcmp(argv[i], "-version") == 0 || qstrcmp(argv[i], "-v") == 0 )
        {
            qWarning( QString( "lupdate version %1 with eZ publish patch %2-nextgen-1" )
                      .arg( QT_VERSION_STR ).arg( QT_VERSION_STR ) );
            return 0;
        }
        else if ( qstrcmp(argv[i], "-ezpublish") == 0 || qstrcmp(argv[i], "-e") == 0 )
        {
            ezpublish = TRUE;
            if ( i+1 < argc )
            {
                i++;
                ezlanguage = argv[i];
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
                        fetchtr_cpp( *t, &fetchedTor, defaultContext, TRUE );
                        metSomething = TRUE;
                    }
                    else if ( it.key() == QString("INTERFACES") ||
                              it.key() == QString("FORMS") )
                    {
                        fetchtr_ui( *t, &fetchedTor, defaultContext, TRUE );
                        fetchtr_cpp( *t + QString(".h"), &fetchedTor,
                                     defaultContext, FALSE );
                        metSomething = TRUE;
                    }
                    else if ( it.key() == QString("TRANSLATIONS") )
                    {
                        translatorFiles.append( *t );
                        metSomething = TRUE;
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

        tfdir.setPath( tfdir.path() + QDir::separator() + ezlanguage );
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

        QFileInfo fi( tfdir.dirName() + "/translation.ts" );
        if ( fi.exists() )
            newTSfile = false;

        MetaTranslator fetchedTor;
        QDir dir;
        if ( verbose )
            qWarning( "Checking eZ publish directory: '%s'...", dir.absPath().latin1() );
//         checkLocale( fetchedTor );
//         traverse( dir, fetchedTor );

//  	    MetaTranslator tor;

//         // Try to find codec from locale file
//         QString codec;
//         QFileInfo localefi( dir.absPath() + "/classes/locale/" + ezlanguage + ".ini" );
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

// 	    tor.load( fi.filePath() );
// 	    if ( verbose )
//             qWarning( "Updating '%s'...", fi.filePath().latin1() );
// 	    merge( &tor, &fetchedTor, verbose );
// 	    if ( noObsolete )
//             tor.stripObsoleteMessages();
// 	    tor.stripEmptyContexts();

// 	    if ( !tor.save( fi.filePath() ) )
//             qWarning( "lupdate error: Cannot save '%s': %s", fi.filePath().latin1(), strerror( errno ) );

//         // Language has not been translated with qt linguist before,
//         // so we must get strings from translated .ini files.
//         if ( newTSfile )
//             importTranslation( tor, fi.filePath() );
 	}

    return 0;
}

/**!
   Recursively traverse an eZ publish directory
*/
void traverse( const QDir &dir, MetaTranslator &fetchedTor )
{
    if ( dir.dirName() == ezoriglanguage )
    {
        if ( verbose )
            qWarning( "   Checking '%s'", dir.path().latin1() );

        const QFileInfoList *list = dir.entryInfoList();
        QFileInfoListIterator it( *list );
        QFileInfo *fi;
        while ( (fi = it.current()) )
        {
            if ( fi->fileName().endsWith( ".ini" ) )
                checkFile( fi, fetchedTor );
            ++it;
        }
        return;
    }

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
        ++it;
    }
}

/**!
   Compares the contents of the original file with the translated file
*/
void checkFile( QFileInfo *origfi, MetaTranslator &fetchedTor )
{
    if ( !origfi || !origfi->exists() )
        return;

    fetchtr_ezpublish( origfi, &fetchedTor, TRUE );
}

/**!
   Compares the contents of the original locale file with the translated file
*/
void checkLocale( MetaTranslator &fetchedTor )
{
    QFileInfo fi( "classes/locale/" + ezoriglanguage + ".ini" );
    if ( verbose )
        qWarning( "   Checking locale '%s'", fi.filePath().latin1() );
    checkFile( &fi, fetchedTor );
}

/**!
   Import translated strings from existing .ini files
*/
void importTranslation( MetaTranslator &tor, const QString &filePath )
{
    qWarning( "This seems to be the first time you are translating this language with Qt Linguist.\n"
              "I will now import your existing translation to the new format." );

    QString context, name, prevname, key, value, content;
    QStringList contentLines;
    QFile tf;
    QTextStream stream;
    QValueList<MetaTranslatorMessage> messages = tor.messages();
    QValueList<MetaTranslatorMessage>::Iterator it = messages.begin();
    while ( it != messages.end() )
    {
        value = QString::null;
        key = (*it).comment();
        key = key.right( key.length() - keyprefix.length() );   // strip "Key: " prefix
        context = (*it).context();
        name = context.left( context.find( " [" ) );            // strip section info
        name.replace( QRegExp( ezoriglanguage ), ezlanguage );  // open translated file, not en_GB
        if ( name.isEmpty() )
            continue;
        else if ( name == prevname )  // same file, no need to open it again
            value = findValue( key, contentLines );
        else                          // new file, close previous and open this one
        {
            tf.close();
            tf.setName( name );
            if ( tf.open( IO_ReadOnly ) )
            {
                prevname = name;
                stream.setDevice( &tf );
                content = stream.read();
                contentLines = QStringList::split( '\n', content );
                value = findValue( key, contentLines );
            }
        }

        if ( !value.isNull() )
        {
            (*it).setTranslation( value );
            (*it).setType( MetaTranslatorMessage::Finished );
            tor.insert( *it );
        }

        ++it;
    }
    tf.close();

    if ( !tor.save( filePath ) )
        qWarning( "lupdate error: Cannot save '%s': %s", filePath.latin1(), strerror( errno ) );
}

/**!
   Finds the value for a given key in a .ini file
*/
const QString findValue( const QString &findkey, QStringList &lines )
{
    QString key, value;
    int equal;
    QStringList::Iterator it = lines.begin();
    while ( it != lines.end() )
    {
        if ( keyValueRE.match( *it ) == 0 )
        {
            equal = (*it).find( "=" );
            key = (*it).left( equal );
            if ( key == findkey )
            {
                value = (*it).right( (*it).length() - equal - 1 );
                value.replace( QRegExp( "\\\\n" ), "\n" );  // newline fix
                return value;
            }
        }
        ++it;
    }
    return QString::null;
}
