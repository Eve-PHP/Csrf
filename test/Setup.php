<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

class Eve_Plugin_Csrf_Setup extends PHPUnit_Framework_TestCase
{
    public function testImport()
    {
        $callback = Eve\Plugin\Csrf\Setup::i()->import();
		
		$this->assertTrue(is_callable($callback));
		
		$error = false;
		try {
			eve()->addCsrf(
				eden('registry'),
				eden('registry'),
				array(
					'method' => 'GET',
					'check_csrf' => true
				)
			);	
		} catch(Exception $e) {
			$error = true;
		}
		
		$this->assertTrue($error);
		
		$error = false;
		$request = eden('registry')->set('get', 'csrf', '1234567890');
		
		try {
			eve()->addCsrf(
				$request,
				eden('registry'),
				array(
					'method' => 'GET',
					'check_csrf' => true,
					'make_csrf' => true
				)
			);	
		} catch(Exception $e) {
			$error = true;
		}
		
		$this->assertFalse($error);
		$this->assertTrue($request->get('get', 'csrf') !== $request->get('csrf'));
		
		$_GET['csrf'] = $_SESSION['csrf'] = $request->get('csrf');
	
		$error = false;
		try {
			$callback($request, eden('registry'));
		} catch(Exception $e) {
			$error = true;
		}
		
		$this->assertFalse($error);
	}
}