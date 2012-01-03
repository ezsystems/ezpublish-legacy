#
# QMake project file for ezlupdate
#
# Gunnstein Lye <gl@ez.no>
# Created on: <4-June-2009 12:51:26 gl>
#
# Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
#

TEMPLATE        = app
TARGET          = ezlupdate

unix {
    macx {
        DESTDIR = ../../../bin/macosx
    }
    !macx {
        DESTDIR = ../../../bin/linux
    }
}
win32:DESTDIR = ../../../bin/win32

QT              -= gui

CONFIG          += qt warn_on release console
CONFIG          -= app_bundle

build_all:!build_pass {
    CONFIG -= build_all
    CONFIG += release
}

include(../shared/formats.pri)
include(../shared/proparser.pri)
include(../shared/translatortools.pri)

OBJECTS_DIR = obj
MOC_DIR     = moc
INCLUDEPATH = . \
              ../shared \
              ../corelib/kernel

SOURCES     += main.cpp

#DEFINES += QT_NO_CAST_TO_ASCII QT_NO_CAST_FROM_ASCII


win32:RC_FILE = winmanifest.rc

embed_manifest_exe:win32-msvc2005 {
    # The default configuration embed_manifest_exe overrides the manifest file
    # already embedded via RC_FILE. Vs2008 already have the necessary manifest entry
    QMAKE_POST_LINK += $$quote(mt.exe -updateresource:$$DESTDIR/lupdate.exe -manifest \"$${PWD}\\lupdate.exe.manifest\")
}

target.path=$$[QT_INSTALL_BINS]
INSTALLS        += target