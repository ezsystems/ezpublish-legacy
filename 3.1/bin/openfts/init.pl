#!/usr/bin/perl
#Example of init new data base
use strict;
use Search::OpenFTS::Index;
use DBI();
use DBD::Pg();

if ( $#ARGV ) {
	print "Usage:\n$0 DATABASE\nIt need to add to postgres contrib/intarray";
	exit;
}

my $dbi =DBI->connect('DBI:Pg:dbname='.$ARGV[0]);
$dbi || die;

$dbi->do("create table txt ( tid int not null primary key, txt varchar, fts_index int[] );") || die;

{
my $idx=Search::OpenFTS::Index->init( 
	dbi=>$dbi, 
	txttid=>'txt.tid',
	use_index_table=>1,
	use_index_array=>'fts_index',
        numbergroup=>10,
	ignore_id_index=>[ qw( 13 14 12 ) ],
	ignore_headline=>[ qw(13 15 16 17 5) ],
	map=>'{ \'19\'=>[1], 18=>[1], 8=>[1], 7=>[1], 6=>[1], 5=>[1], 4=>[1], }',
        dict=>[
                'Search::OpenFTS::Dict::PorterEng',
                'Search::OpenFTS::Dict::UnknownDict',
	] 
);
die "QQQ: $@" if ! $idx;

	#here we can index files by $idx->index;
	#it must be speedy, becouse table indexes not be created yet

$idx->create_index; #this function called automatically when destroy object $idx 
}
$dbi->disconnect;

