<?php

function test1() {
	$safeKeys = [
		'foo' => $_GET['x'],
		'bar' => $_GET['baz']
	];

	foreach ( $safeKeys as $k => $v ) {
		echo $k; // Safe
		echo $v; // Unsafe
	}
}

function test2() {
	$unsafeKeys = [
		$_GET['a'] => 'safe',
		$_GET['b'] => 'unsafe'
	];

	foreach ( $unsafeKeys as $k => $v ) {
		echo $k; // Unsafe
		echo $v; // Safe
	}
}

function test3() {
	$allUnsafe = [
		$_GET['a'] => $_GET['a'],
		$_GET['b'] => $_GET['b']
	];

	foreach ( $allUnsafe as $k => $v ) {
		echo $k; // Unsafe
		echo $v; // Unsafe
	}
}

function test4() {
	$unknownSafeKeys = [
		htmlspecialchars( $_GET['a'] ) => 'safe',
		htmlspecialchars( $_GET['b'] ) => 'unsafe'
	];

	foreach ( $unknownSafeKeys as $k => $v ) {
		echo $k; // Safe because values are safe
		echo $v; // Safe
	}
}

function test5() {
	$unknownSafeKeysUnsafeValues = [
		htmlspecialchars( $_GET['a'] ) => $_GET['a'],
		htmlspecialchars( $_GET['b'] ) => $_GET['b']
	];

	foreach ( $unknownSafeKeysUnsafeValues as $k => $v ) {
		echo $k; // Safe
		echo $v; // Unsafe
	}
}
