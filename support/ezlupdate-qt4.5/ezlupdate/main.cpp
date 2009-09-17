//
// main.cpp for ezlupdate
//
// This file is based on main.cpp from lupdate/Qt Linguist,
// which is Copyright (C) 2009 Trolltech AS (www.trolltech.com).
//
// Gunnstein Lye <gl@ez.no>
// Created on: <4-Jun-2009 13:27:17 gl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

#include <iostream>

#include "translator.h"
#include "translatortools.h"
#include "profileevaluator.h"

#include <QtCore/QCoreApplication>
#include <QtCore/QDebug>
#include <QtCore/QDir>
#include <QtCore/QFile>
#include <QtCore/QFileInfo>
#include <QtCore/QString>
#include <QtCore/QStringList>
#include <QtCore/QTextCodec>

static QString m_defaultExtensions;

static void printOut( const QString & out )
{
    std::cout << out.toUtf8().data() << std::endl;
}

// Recursively traverse an eZ Publish directory
static void traverse( const QDir &dir, Translator &fetchedTor, ConversionData cd, UpdateOptions options, bool *fail )
{
    if ( options & Verbose )
        printOut( QObject::tr( "   Checking subdirectory '%1'" ).arg( qPrintable(dir.path()) ) );

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
            traverse( subdir.currentPath(), fetchedTor, cd, options, fail );
            subdir.setCurrent( dir.path() );
        }
        else
        {
            if ( fi.fileName().endsWith(QLatin1String(".php"), Qt::CaseInsensitive) )
            {
                if ( options & Verbose )
                    printOut( QObject::tr( "      Checking '%1'" ).arg( qPrintable(fi.fileName()) ) );
                if ( !fetchedTor.load(fi.fileName(), cd, QLatin1String("php")) )
                {
                    qWarning( "%s", qPrintable( cd.error() ) );
                    *fail = true;
                }
            }
            else if ( fi.fileName().endsWith(QLatin1String(".tpl"), Qt::CaseInsensitive) )
            {
                if ( options & Verbose )
                    printOut( QObject::tr( "      Checking '%1'" ).arg( qPrintable(fi.fileName()) ) );
                if ( !fetchedTor.load(fi.fileName(), cd, QLatin1String("tpl")) )
                {
                    qWarning( "%s", qPrintable( cd.error() ) );
                    *fail = true;
                }
            }
        }
    }
}

static void recursiveFileInfoList(const QDir &dir,
    const QStringList &nameFilters, QDir::Filters filter, bool recursive,
    QFileInfoList *fileinfolist)
{
    if (recursive)
        filter |= QDir::AllDirs;
    QFileInfoList entries = dir.entryInfoList(nameFilters, filter);

    QFileInfoList::iterator it;
    for (it = entries.begin(); it != entries.end(); ++it) {
        QString fname = it->fileName();
        if (fname != QLatin1String(".") && fname != QLatin1String("..")) {
            if (it->isDir())
                recursiveFileInfoList(QDir(it->absoluteFilePath()), nameFilters, filter, recursive, fileinfolist);
            else
                fileinfolist->append(*it);
        }
    }
}

static QString version = "4.2.0rc2"; // eZ Publish version plus local version
static QStringList dirs;          // Additional scan directories
static QDir extension_dir;        // Extension directory
static QRegExp localeRE( "^[a-z]{3}-[A-Z]{2}(@.*)?$" );

static void printUsage()
{
    printOut( QObject::tr(
        "Creates or updates eZ Publish translations.\n"
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
        "    --version                 Display the version of ezlupdate and exit\n"
    ) );
}

static void updateTsFiles(const Translator &fetchedTor, const QStringList &tsFileNames,
    const QByteArray &codecForTr, const QString &sourceLanguage, const QString &targetLanguage,
    UpdateOptions options, bool *fail)
{
    QDir dir;
    QString err;
    foreach (const QString &fileName, tsFileNames) {
        QString fn = dir.relativeFilePath(fileName);
        ConversionData cd;
        Translator tor;
        cd.m_sortContexts = !(options & NoSort);
        if (QFile(fileName).exists()) {
            if (!tor.load(fileName, cd, QLatin1String("auto"))) {
                qWarning( "%s", qPrintable( cd.error() ) );
                *fail = true;
                continue;
            }
            tor.resolveDuplicates();
            cd.clearErrors();
            if (!codecForTr.isEmpty() && codecForTr != tor.codecName())
                qWarning("lupdate warning: Codec for tr() '%s' disagrees with "
                         "existing file's codec '%s'. Expect trouble.",
                         codecForTr.constData(), tor.codecName().constData());
            if (!targetLanguage.isEmpty() && targetLanguage != tor.languageCode())
                qWarning("lupdate warning: Specified target language '%s' disagrees with "
                         "existing file's language '%s'. Ignoring.",
                         qPrintable(targetLanguage), qPrintable(tor.languageCode()));
            if (!sourceLanguage.isEmpty() && sourceLanguage != tor.sourceLanguageCode())
                qWarning("lupdate warning: Specified source language '%s' disagrees with "
                         "existing file's language '%s'. Ignoring.",
                         qPrintable(sourceLanguage), qPrintable(tor.sourceLanguageCode()));
        } else {
            if (!codecForTr.isEmpty())
                tor.setCodecName(codecForTr);
            if (!targetLanguage.isEmpty())
                tor.setLanguageCode(targetLanguage);
            if (!sourceLanguage.isEmpty())
                tor.setSourceLanguageCode(sourceLanguage);
        }
        tor.makeFileNamesAbsolute(QFileInfo(fileName).absoluteDir());
        if (options & NoLocations)
            tor.setLocationsType(Translator::NoLocations);
        else if (options & RelativeLocations)
            tor.setLocationsType(Translator::RelativeLocations);
        else if (options & AbsoluteLocations)
            tor.setLocationsType(Translator::AbsoluteLocations);
        if (options & Verbose)
            printOut(QObject::tr("Updating '%1'...\n").arg(fn));

        if (tor.locationsType() == Translator::NoLocations) // Could be set from file
            options |= NoLocations;
        Translator out = merge(tor, fetchedTor, options, err);
        if (!codecForTr.isEmpty())
            out.setCodecName(codecForTr);

        if ((options & Verbose) && !err.isEmpty()) {
            printOut( qPrintable( err ) );
            err.clear();
        }
        if (options & PluralOnly) {
            if (options & Verbose)
                printOut(QObject::tr("Stripping non plural forms in '%1'...\n").arg(fn));
            out.stripNonPluralForms();
        }
        if (options & NoObsolete)
            out.stripObsoleteMessages();
        out.stripEmptyContexts();

        if (!out.save(fileName, cd, QLatin1String("auto"))) {
            qWarning( "%s", qPrintable( cd.error() ) );
            *fail = true;
        }
    }
}

int main(int argc, char **argv)
{
    QCoreApplication app(argc, argv);
    m_defaultExtensions = QLatin1String("php,tpl");

    QStringList args = app.arguments();
    QString defaultContext; // This was QLatin1String("@default") before.
    Translator fetchedTor;
    QByteArray codecForTr;
    QByteArray codecForSource;
    QStringList tsFileNames;
    QStringList proFiles;
    QStringList sourceFiles;
    QString targetLanguage;
    QString sourceLanguage;

    UpdateOptions options =
        HeuristicSameText | HeuristicSimilarText | HeuristicNumber;
    bool standardSyntax = true;

    QString extensions = m_defaultExtensions;
    QStringList extensionsNameFilters;

    for (int  i = 1; i < argc; ++i) {
        if (args.at(i) == QLatin1String("-ts"))
            standardSyntax = false;
    }

    QStringList languages;
    for (int i = 1; i < argc; ++i)
    {
        QString arg = args.at(i);
        if (arg == QLatin1String("--help")
                || arg == QLatin1String("-h"))
        {
            printUsage();
            return 0;
        }
        else if (arg == QLatin1String("--noobsolete")
                || arg == QLatin1String("-no"))
        {
            options |= NoObsolete;
            continue;
        }
        else if (arg == QLatin1String("--verbose")
                || arg == QLatin1String("-v"))
        {
            options |= Verbose;
            continue;
        }
        else if (arg == QLatin1String("--version"))
        {
            printOut(QObject::tr("ezlupdate version %1-%2").arg(QLatin1String(QT_VERSION_STR)).arg(version));
            return 0;
        }
        else if (arg == QLatin1String("--untranslated")
                || arg == QLatin1String("-u"))
        {
            options |= Untranslated;
            continue;
        }
        else if (arg == QLatin1String("--extension")
                || arg == QLatin1String("-e"))
        {
            if ( i < argc - 1 )
            {
                i++;
                QString arg( argv[i] );
                extension_dir.setPath( arg );
                if ( !arg.startsWith( "-" ) && extension_dir.exists() )
                {
                    printOut( QObject::tr( "Extension mode, directory: %1" ).arg( qPrintable(arg) ) );
                    options |= Extension;
                    continue;
                }
                else
                {
                    qFatal( "ERROR: Directory does not exist: %s", qPrintable(arg));
                }
            }
            else
            {
                qFatal( "ERROR: Extension directory missing" );
            }
        }
        else if (arg == QLatin1String("--dirs")
                || arg == QLatin1String("-d"))
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
                    printOut( QObject::tr( "Added scan directory: %1" ).arg( qPrintable(arg) ) );
                    dirs.append( arg );
                }
                i++;
            }
            continue;
        }
        else if (arg == QLatin1String("--utf8"))
        {
            options |= AssumeUTF8;
            continue;
        }
        else if (arg.startsWith(QLatin1String("-")) && arg != QLatin1String("-"))
        {
            qWarning("Unrecognized option '%s'", qPrintable(arg));
            return 1;
        }
        else
        {
            QString language = argv[i];
            if ( localeRE.indexIn( language ) == -1 )
            {
                qFatal( "ERROR - Locale should be of the form aaa-AA or aaa-AA@variation. Examples: eng-GB, nor-NO, srp-RS@latin" );
            }
            else
                languages.append( language );
        }
    } // for args

    if ( options & Untranslated )
    {
        // Add the untranslated file to the list
        languages.append( "untranslated" );
    }

    if (argc == 1)
    {
        printUsage();
        return 1;
    }

    if ( languages.count() == 0 )
    {
        qFatal( "ERROR - No languages defined, cannot continue." );
        return 1;
    }

    // Create/verify translation directory
    QDir tfdir( "share/translations" );
    if ( options & Extension )
        tfdir.setPath( extension_dir.path() + QDir::separator() + "translations" );

    if ( !tfdir.exists() )
    {
        if ( QDir::current().mkdir( tfdir.path() ) )
        {
            printOut( QObject::tr( "eZ Publish translations directory created: %1" ).arg( qPrintable(tfdir.path()) ) );
        }
        else
        {
            qFatal( "ERROR - eZ Publish translations directory could not be created: %s", qPrintable(tfdir.path()) );
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
                printOut( QObject::tr( "eZ Publish translations directory created: %1" ).arg( qPrintable(languageDir.path()) ) );
            }
            else
            {
                qFatal( "ERROR - eZ Publish translations directory could not be created: %s", qPrintable(languageDir.path()) );
            }
        }
    }

    bool fail = false;
    ConversionData cd;
    cd.m_defaultContext = defaultContext;
    cd.m_noUiLines = options & NoUiLines;
    cd.m_assumeUtf8 = options & AssumeUTF8;
    QStringList tsFiles = tsFileNames;

    // Start the real job
    QDir dir;
    QString currentPath = dir.absolutePath();
    if ( options & Extension )
    {
        if ( options & Verbose )
            printOut( QObject::tr( "Checking eZ Publish extension directory: '%1'" ).arg( qPrintable(extension_dir.absolutePath()) ) );
        dir.setCurrent( extension_dir.absolutePath() );
        traverse( dir.currentPath(), fetchedTor, cd, options, &fail );
    }
    else
    {
        if ( options & Verbose )
            printOut( QObject::tr( "Checking eZ Publish directory: '%1'" ).arg( qPrintable(dir.absolutePath()) ) );
//        traverse( dir.path() + QDir::separator() + "kernel", fetchedTor, cd, options, &fail );
//        traverse( dir.path() + QDir::separator() + "lib", fetchedTor, cd, options, &fail );
//        traverse( dir.path() + QDir::separator() + "design", fetchedTor, cd, options, &fail );

        // Fix for bug in qt win free, only reads content of current directory
        dir.setCurrent( currentPath + "/kernel" );
        traverse( dir.currentPath(), fetchedTor, cd, options, &fail );
        dir.setCurrent( currentPath + "/lib" );
        traverse( dir.currentPath(), fetchedTor, cd, options, &fail );
        dir.setCurrent( currentPath + "/design" );
        traverse( dir.currentPath(), fetchedTor, cd, options, &fail );
    }

    // Additional directories
    dir.setCurrent( currentPath );
    for ( QStringList::Iterator it = dirs.begin(); it != dirs.end(); ++it )
    {
        dir.setCurrent( *it );
        traverse( dir.currentPath(), fetchedTor, cd, options, &fail );
    }

    // Update TS files
    for ( QStringList::ConstIterator it = languages.begin(); it != languages.end(); ++it )
    {
        const QString &language = *it;
        QFileInfo fi( tfdir.path() + QDir::separator() + language + QDir::separator() + "translation.ts" );
        tsFiles += fi.filePath();
        updateTsFiles( fetchedTor, tsFiles, codecForTr, sourceLanguage, targetLanguage, options, &fail );
    }

    return fail ? 1 : 0;
}
