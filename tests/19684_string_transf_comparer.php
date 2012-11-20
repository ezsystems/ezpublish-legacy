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

/*
 preg_replace ": 0.00011000 : L avertissement
 preg_replace ': 0.00004100 : L avertissement
 strtr        ": 0.00001200 : L avertissement
 strtr        ': 0.00000500 : L’avertissement
 str_replace  ": 0.00000900 : L avertissement
 str_replace  ': 0.00000400 : L’avertissement
*/

?>

