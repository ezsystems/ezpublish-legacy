<?php

$text = 'L’avertissement'; 

$mtime = microtime();
$textd = preg_replace(array("#\xe2\x80\x99#", "#\xe2\x80\x98#", "#\xe2\x80\x9c#", "#\xe2\x80\x9d#"), " ", $text );
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n preg_replace \": $tdiff : $textd");

$mtime = microtime();
$textd = preg_replace(array('#\xe2\x80\x99#', '#\xe2\x80\x98#', '#\xe2\x80\x9c#', '#\xe2\x80\x9d#'), " ", $text );
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n preg_replace ': $tdiff : $textd");

$mtime = microtime();
$textd = strtr ($text, array("\xe2\x80\x99" => ' ', "\xe2\x80\x98" => ' ', "\xe2\x80\x9c" => ' ', "\xe2\x80\x9d" =>  ' '));
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n strtr        \": $tdiff : $textd");

$mtime = microtime();
$textd = strtr ($text, array('\xe2\x80\x99' => ' ', '\xe2\x80\x98' => ' ', '\xe2\x80\x9c' => ' ', '\xe2\x80\x9d' => ' '));
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n strtr        ': $tdiff : $textd");

$mtime = microtime();
$textd = str_replace(array("\xe2\x80\x99", "\xe2\x80\x98", "\xe2\x80\x9c", "\xe2\x80\x9d"), ' ', $text );
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n str_replace  \": $tdiff : $textd");

$mtime = microtime();
$textd = str_replace(array('\xe2\x80\x99', '\xe2\x80\x98', '\xe2\x80\x9c', '\xe2\x80\x9d'), " ", $text );
$tdiff  = number_format(microtime() - $mtime, 8);
print("\n str_replace  ': $tdiff : $textd");
print("\n");

?>