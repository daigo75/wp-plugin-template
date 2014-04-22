<?php

use Aelia\EDD\Messages;

class Messages_Test extends WP_UnitTestCase {
	const MSG_CODE = 'test_msg_code';
	const MESSAGE = 'Test Message';

	public function setUp() {
		parent::setUp();
		$this->aelia_messages = new Messages();
	}

	public function test_add_error_message() {
		$this->aelia_messages->add_error_message(self::MSG_CODE, self::MESSAGE);
		// The above method doesn't return a value. By just asserting "True" we are
		// just checking that the above worked and didn't return an error or throw
		// an exception
		$this->assertTrue(true);
	}

	public function test_get_error_message() {
		$this->aelia_messages->add_error_message(self::MSG_CODE, self::MESSAGE);
		$this->assertEquals($this->aelia_messages->get_error_message(self::MSG_CODE), self::MESSAGE);
	}

	public function test_load_error_messages() {
		$this->aelia_messages->load_error_messages();
		$message = $this->aelia_messages->get_error_message(Messages::ERR_FILE_NOT_FOUND);
		$this->assertTrue(!empty($message));
	}
}
