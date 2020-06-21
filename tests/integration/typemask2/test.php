<?php

class Foo {
	public $unknownType;
}

class Bar {
	public $localProp;

	function certainlyUnsafe() {
		$f = new Foo;
		$f->unknownType = $_GET['baz'];
		echo $f->unknownType;
	}

	function potentiallyUnsafe() {
		$f = new Foo;
		$f->unknownType = $_GET['baz'];
		if ( is_int( $f->unknownType ) ) {
			// Phan doesn't infer the real type here, because it might change again.
			echo $f->unknownType;
		}
	}

	function certainlyUnsafeLocal() {
		$this->localProp = $_GET['foo'];
		echo $this->localProp;
	}

	function safeLocal() {
		$this->localProp = $_GET['baz'];
		if ( is_int( $this->localProp ) ) {
			// Same here, even if it's a property of $this
			echo $this->localProp;
		}
	}
}

$globalVar = 'foo';

class ModifyGlobal {
	function main() {
		global $globalVar;
		$globalVar = $_GET['foo'];
	}
}

class Baz {
	function echoGlobal() {
		global $globalVar;
		echo $globalVar;
	}
}

echo $globalVar;

