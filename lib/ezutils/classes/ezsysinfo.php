<?php
//
// Definition of eZSysInfo class
//
// Created on: <29-Sep-2004 12:43:57 jb>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZSysInfo ezsysinfo.php
  \brief Provides common information on the running system

  The following information can be queried:
  - CPU Type (e.g Pentium) - cpuType()
  - CPU Speed (e.g 1000) - cpuSpeed()
  - CPU Unit (e.g. MHz) - cpuUnit()
  - Memory Size in bytes (e.g. 528424960) - memorySize()

  \code
  $info = new eZSysInfo();
  $info->scan();
  print( $info->cpuType() . "\n" );
  \endcode

  \note This class supports the 'attribute' system and be used directly as a template variable.
  \note It uses eZSys to figure out the OS type.
*/

class eZSysInfo
{
    /*!
     Constructor
    */
    function eZSysInfo()
    {
    }

    /*!
     \return An array with available attributes.
     The available attributes:
     - cpu_type - cpuType()
     - cpu_unit - cpuUnit()
     - cpu_speed - cpuSpeed()
     - memory_size - memorySize()
    */
    function attributes()
    {
        return array( 'is_valid',
                      'cpu_type',
                      'cpu_unit',
                      'cpu_speed',
                      'memory_size' );
    }

    /*!
     \return \c true if the attribute named \a $name exists.
     See attributes() for a list of available attributes.
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
     \return The value of the attribute named \a $name, or \c null if it does not exist.
     See attributes() for a list of available attributes.
    */
    function attribute( $name )
    {
        if ( $name == 'is_valid' )
            return $this->IsValid;
        else if ( $name == 'cpu_type' )
            return $this->CPUType;
        else if ( $name == 'cpu_unit' )
            return $this->CPUUnit;
        else if ( $name == 'cpu_speed' )
            return $this->CPUSpeed;
        else if ( $name == 'memory_size' )
            return $this->MemorySize;
        else
        {
            eZDebug::writeError( "Attribute '$name' does not exist", 'eZSysInfo::attribute' );
            return null;
        }
    }

    /*!
     \return \c true if the system has been scanned correctly.
    */
    function isValid()
    {
        return $this->IsValid;
    }

    /*!
     Contains the type of CPU, the type is taken directly from the OS
     and can vary a lot.
     \return The type as a string or \c false if no type was found.
    */
    function cpuType()
    {
        return $this->CPUType;
    }

    /*!
     Contains the speed of CPU, the type is taken directly from the OS
     and can vary a lot. The speed is just a number so use cpuUnit()
     to get the proper unit (e.g MHz).
     \return The speed as a string or \c false if no type was found.
    */
    function cpuSpeed()
    {
        return $this->CPUSpeed;
    }

    /*!
     Contains the amount of system memory the OS has, the value is
     in bytes.
     \return The type as a number \c false if no type was found.
    */
    function memorySize()
    {
        return $this->MemorySize;
    }

    /*!
     Scans the system depending on the OS and fills in the information internally.
     \return \c true if it was able to scan the system or \c false if it failed.
    */
    function scan()
    {
        $this->IsValid = false;
        $this->CPUSpeed = false;
        $this->CPUType = false;
        $this->CPUUnit = false;
        $this->MemorySize = false;

        $sys = eZSys::instance();
        $osType = $sys->osType();

        if ( $osType == 'win32' )
        {
            // Windows (win32) is not supported yet
            // eZDebug::writeWarning( "System scan for Windows (win32) machines not supported yet" );
        }
        else if ( $osType == 'mac' )
        {
            // Mac means FreeBSD type of structure?
            $this->IsValid = $this->scanDMesg();
            return $this->IsValid;
        }
        else if ( $osType == 'unix' )
        {
            // Now determine specific 'Unix' type
            $osName = $sys->osName();
            if ( $osName == 'linux' )
            {
                $this->IsValid = $this->scanProc();
                return $this->IsValid;
            }
            else if ( $osName == 'freebsd' )
            {
                $this->IsValid = $this->scanDMesg();
                return $this->IsValid;
            }
            else
            {
                // Not known so we just try the various scanners
                //
                // /proc for Linux systems
                if ( $this->scanProc() )
                {
                    $this->IsValid = true;
                    return true;
                }
                // dmesg.boot for FreeBSD systems
                if ( $this->scanDMesg() )
                {
                    $this->IsValid = true;
                    return true;
                }
            }
        }
        return false;
    }

    /*!
     \private
     Scans the /proc/cpuinfo and /proc/meminfo files for CPU
     and memory information.
     If this files are unavailable or could not be read it will return \c false.
     \param $cpuinfoPath The path to the cpuinfo file, if \c false it uses '/proc/cpuinfo' which should be sufficient.
     \param $meminfoPath The path to the meminfo file, if \c false it uses '/proc/meminfo' which should be sufficient.
    */
    function scanProc( $cpuinfoPath = false, $meminfoPath = false )
    {
        if ( !$cpuinfoPath )
            $cpuinfoPath = '/proc/cpuinfo';
        if ( !$meminfoPath )
            $meminfoPath = '/proc/meminfo';

        if ( !file_exists( $cpuinfoPath ) )
            return false;
        if ( !file_exists( $meminfoPath ) )
            return false;

        $fileLines = file( $cpuinfoPath );
        foreach ( $fileLines as $line )
        {
            if ( substr( $line, 0, 7 ) == 'cpu MHz' )
            {
                $cpu = trim( substr( $line, 11, strlen( $line ) - 11 ) );
                $this->CPUSpeed = $cpu;
                $this->CPUUnit = 'MHz';
            }
            if ( substr( $line, 0, 10 ) == 'model name' )
            {
                $system = trim( substr( $line, 13, strlen( $line ) - 13 ) );
                $this->CPUType = $system;
            }
            if ( $this->CPUSpeed !== false and
                 $this->CPUType !== false and
                 $this->CPUUnit !== false )
                break;
        }

        $fileLines = file( $meminfoPath );
        foreach ( $fileLines as $line )
        {
            if ( substr( $line, 0, 8 ) == 'MemTotal' )
            {
                $mem = trim( substr( $line, 11, strlen( $line ) - 11 ) );
                $memBytes = $mem;
                if ( preg_match( "#^([0-9]+) *([a-zA-Z]+)#", $mem, $matches ) )
                {
                    $memBytes = (int)$matches[1];
                    $unit = strtolower( $matches[2] );
                    if ( $unit == 'kb' )
                    {
                        $memBytes *= 1024;
                    }
                    else if ( $unit == 'mb' )
                    {
                        $memBytes *= 1024*1024;
                    }
                    else if ( $unit == 'gb' )
                    {
                        $memBytes *= 1024*1024*1024;
                    }
                }
                else
                {
                    $memBytes = (int)$memBytes;
                }
                $this->MemorySize = $memBytes;
            }
            if ( $this->MemorySize !== false )
                break;
        }

        return true;
    }

    /*!
     \private
     Scans the dmesg.boot file which is created by the kernel.
     If this files are unavailable or could not be read it will return \c false.
     \param $dmesgPath The path to the dmesg file, if \c false it uses '/var/run/dmesg.boot' which should be sufficient.
    */
    function scanDMesg( $dmesgPath = false )
    {
        if ( !$dmesgPath )
            $dmesgPath = '/var/run/dmesg.boot';
        if ( !file_exists( $dmesgPath ) )
            return false;
        $fileLines = file( $dmesgPath );
        foreach ( $fileLines as $line )
        {
            if ( substr( $line, 0, 3 ) == 'CPU' )
            {
                $system = trim( substr( $line, 4, strlen( $line ) - 4 ) );
                $cpu = false;
                $cpuunit = false;
                if ( preg_match( "#^(.+)\\((.+)(MHz) +([^)]+)\\)#", $system, $matches ) )
                {
                    $system = trim( $matches[1] ) . ' (' . trim( $matches[4] ) . ')';
                    $cpu = $matches[2];
                    $cpuunit = $matches[3];
                }
                $this->CPUSpeed = $cpu;
                $this->CPUType = $system;
                $this->CPUUnit = $cpuunit;
            }
            if ( substr( $line, 0, 11 ) == 'real memory' )
            {
                $mem = trim( substr( $line, 12, strlen( $line ) - 12 ) );
                $memBytes = $mem;
                if ( preg_match( "#^= *([0-9]+)#", $mem, $matches ) )
                {
                    $memBytes = $matches[1];
                }
                $memBytes = (int)$memBytes;
                $this->MemorySize = $memBytes;
            }
            if ( $this->CPUSpeed !== false and
                 $this->CPUType !== false and
                 $this->CPUUnit !== false and
                 $this->MemorySize !== false )
                break;

        }
        return true;
    }

    /// \privatesection
    public $IsValid = false;
    public $CPUSpeed = false;
    public $CPUType = false;
    public $CPUUnit = false;
    public $MemorySize = false;
}

?>
