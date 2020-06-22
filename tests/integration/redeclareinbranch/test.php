<?php

class Foo {
	public function show() {
		echo $this->bar();
	}

	/**
	 * @return string
	 */
	private function bar() {
		if ( rand() ) {
			// This is obviously safe due to reassigning, but we have to ensure
			// that taintedness is overwritten in BranchScope.
			$form = $_GET['bar'];
			$form = 'foo';
			return $form;
		}
		return 'foo';
	}

	public function output() {
		$form = '';
		if ( rand() ) {
			$form = $_GET['bar'];
			$form = 'foo';
			echo $form; // Safe, but we're not yet smart enough
			$form = $_GET['baz'];
		}
		echo $form; // Unsafe. Ideally, this shouldn't have line 25 in its caused-by
	}
}
