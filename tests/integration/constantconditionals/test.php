<?php

/* Tests for constant conditionals. These are not fully implemented yet, mainly due to upstream limitations. */

function neverTainted( $arg ) {
	if ( false ) {
		$arg = $_GET['x'];
	}
	echo $arg; // Safe
}

function alwaysTainted( $arg ) {
	if ( true ) {
		$arg = $_GET['x'];
	}
	echo $arg; // Unsafe
}

function neverEscaped( $arg ) {
	if ( rand() ) {
		$arg = $_GET['x'];
	}
	if ( false ) {
		$arg = htmlspecialchars( $arg );
	}
	echo $arg; // Unsafe
}

function alwaysEscaped( $arg ) {
	if ( rand() ) {
		$arg = $_GET['x'];
	}
	if ( true ) {
		$arg = htmlspecialchars( $arg );
	}
	echo $arg; // Safe
}

function neverTainted2( $arg ) {
	if ( true ) {
		if ( rand() ) {
			if ( false ) {
				$arg .= $_GET['x'];
			}
		} else {
			$arg = 'safe';
		}
	}
	if ( rand() ) {
		$arg = htmlspecialchars( $arg );
	}
	echo $arg; // Safe
}

function neverTaintedRef( &$ref ) {
	if ( false ) {
		$ref = $_GET['x'];
	}
}
function alwaysTaintedRef( &$ref ) {
	if ( true ) {
		$ref = $_GET['x'];
	}
}
function neverEscapedRef( &$ref ) {
	if ( false ) {
		$ref = htmlspecialchars( $ref );
	}
}
function alwaysEscapedRef( &$ref ) {
	if ( true ) {
		$ref = htmlspecialchars( $ref );
	}
}

function safe1() {
	$ref = 'x';
	neverTaintedRef( $ref );
	echo $ref; // TODO: This shouldn't be reported (https://github.com/phan/phan/issues/3965)
}

function unsafe1() {
	$ref = 'x';
	alwaysTaintedRef( $ref );
	echo $ref;
}

function unsafe2() {
	$ref = $_GET['foo'];
	neverEscapedRef( $ref );
	echo $ref; // TODO: This should be reported (https://github.com/phan/phan/issues/3965)
}

function safe2() {
	$ref = $_GET['foo'];
	alwaysEscapedRef( $ref );
	echo $ref;
}
