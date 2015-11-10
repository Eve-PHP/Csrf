<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eve\Csrf;

use Eden\Registry\Index as Registry;

/**
 * CSRF Middleware Plugin Class
 *
 * @package  Eve
 * @category Plugin
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Plugin extends Base
{
	/**
	 * @const string FAIL_400 Error Template
	 */
	const FAIL_400 = 'We prevented a potential attack on our servers coming from the request you just sent us.';

    /**
     * Main route method
     *
     * @return function
     */
    public function import($escape = '1234567890')
    {
        //remember this scope
        $self = $this;
		$message = self::FAIL_400;
        
        eve()->addMethod('addCsrf', function (
            Registry $request,
            Registry $response,
            array $meta
        ) use ($self, $message) {
            //we already checked the csrf it's good
            //we just need to check if it's set
            
            //testing GET
            if (isset($meta['check_csrf'])
                && $meta['check_csrf']
                && $meta['method'] === 'GET'
                && !$request->isKey('get', 'csrf')
            ) {
                Exception::i($message)->trigger();
            }
            
            //testing POST
            if (isset($meta['check_csrf'])
                && $meta['check_csrf']
                && $meta['method'] === 'POST'
                && !$meta->isKey('post', 'csrf')
            ) {
                Exception::i($message)->trigger();
            }
            
            //this is for ALL
            if (isset($meta['check_csrf'])
                && $meta['check_csrf']
                && $meta['method'] === 'ALL'
                && !empty($_POST)
                && !$request->isKey('post', 'csrf')
            ) {
                Exception::i($message)->trigger();
            }
            
            //set csrf
            if (isset($meta['make_csrf']) && $meta['make_csrf']) {
                $request->set('csrf', md5(uniqid()));
            } else if (isset($meta['copy_csrf']) && $meta['copy_csrf']) {
                $request->set('csrf', $_SESSION['csrf']);
            }
        });
        
        //You can add validators here
        return function (Registry $request, Registry $response) use ($self, $escape, $message) {
            //CSRF - whether or not we are expecting it lets do a check
            $csrf = false;
            
            if ($request->isKey('get', 'csrf')) {
                $csrf = $request->get('get', 'csrf');
            } else if ($request->isKey('post', 'csrf')) {
                $csrf = $request->get('post', 'csrf');
            }
            
            if ($csrf !== false
                && $csrf !== $_SESSION['csrf']
                && $csrf !== $escape
            ) {
                Exception::i($message)->trigger();
            }
        };
    }
}
