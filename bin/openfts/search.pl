#!/usr/bin/perl

use strict;
use Search::OpenFTS;
use locale;
use DBI();
use DBD::Pg();
use Getopt::Std;
use Time::HiRes qw( usleep ualarm gettimeofday tv_interval );

my %opt;
getopts('b:l:p:vqedz', \%opt);

if ( $#ARGV<0 || ! $opt{p} ) {
	print "Usage\n";
	print "$0 -p DATABASE [-b count] [ -v ] [ -l ] [ -q | -d ] [ -e ] WORD1 [ WORD2 [...] ]\n";
	print "\t-b count\tbenchmark\n";
	print "\t-v\t\tverbose\n";
	print "\t-q\t\tquiet\n";
	print "\t-d\t\tdetailed view\n";
	print "\t-l\t\tlimit frequency\n";
	print "\t-e\t\texplain\n";
	exit;
}

my $dbi=DBI->connect('DBI:Pg:dbname='.$opt{p}.';host=sp',"sp");
$dbi||die;

my %ofts;
if ( defined $opt{l} ) {
	$ofts{limit_freq}=$opt{l};
}
 
my $fts=Search::OpenFTS->new( $dbi, %ofts );
die $@ if ! $fts;

if ( $opt{v} ) {
	print $fts->_sql( \@ARGV ),"\n";
}
if ( $opt{e} ) {
	my $sql = $fts->_sql( \@ARGV );
	if ( length $sql ) {
		$dbi->do("explain $sql" );
	} else {
		print "No SQL\n";
	}
}

if ( $opt{d} ) {
	my ($out, $tables, $condition, $order) = $fts->get_sql( \@ARGV );
	print "$out ,\n $tables ,\n $condition ,\n $order \n";
	my $sql="
select
        txt.tid,
        txt.txt,
        $out
from
        txt$tables
where
        $condition
order by $order;";
	foreach ( exec_sql( $dbi, $sql ) ) {
		print "---------------------------------------------------\n";
		print "TID: $_->{tid}\tpos: $_->{pos}\n$_->{txt}"; 
	}
} 
#elsif ( ! $opt{q} ) {
#	my $a=$fts->search( \@ARGV );
#	print join(";", @$a),"\n" if ref $a;
#} 


if (  $opt{z} ) {
	my ($out, $tables, $condition, $order) = $fts->get_sql( \@ARGV );
	print "$out ||| $tables ||| $condition ||| $order \n";
} 

#потестируем на скорость
if ( $opt{b} ) {
	print "Speed gun in use :)...\n" if ! $opt{q};
	my $t0 = [gettimeofday];
	my $count=0;
	foreach ( 1..$opt{b} ) {
		my $a=$fts->search( \@ARGV );
		$count=$#{$a};
	}
	my $elapsed = tv_interval ( $t0, [gettimeofday]);
	print "Done\n";
	print sprintf("total: %.02f sec; number: %d; for one: %.03f sec; found %d docs\n", $elapsed, $opt{b}, $elapsed/$opt{b}, $count+1 );
} 
$dbi -> disconnect;

sub exec_sql {
        my ($dbi, $sql, @keys) = @_;
        my $sth=$dbi->prepare($sql) || die;
        $sth->execute( @keys ) || die; 
        my $r;  
        my @row;
        while ( defined ( $r=$sth->fetchrow_hashref ) ) {
                push @row, $r;
        }               
        $sth->finish;   
        return @row;
}

