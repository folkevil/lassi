<?php namespace Lassi\App;

/**
 * Util
 *
 * @author Jabran Rafique <hello@jabran.me>
 * @license MIT License
 */

use \Exception;

class Util {

	/**
	 * Set app env variables
	 * @return void
	 */
	public static function setEnvVariables($root = '/') {
		$configs = null;

		// Set custom handler to catch errors as exceptions
		set_error_handler(
		    create_function(
		        '$severity, $message, $file, $line',
		        'throw new ErrorException($message, $severity, $severity, $file, $line);'
		    )
		);

		if ( file_exists($root . '/.dev.env') && is_readable($root . '/.dev.env') ) {
			try {
				$configs = file_get_contents($root . '/.dev.env');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
		else if ( file_exists($root . '/.dist.env') && is_readable($root . '/.dist.env') ) {
			try {
				$configs = file_get_contents($root . '/.dist.env');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
		else if ( file_exists($root . '/.env') && is_readable($root . '/.env') ) {
			try {
				$configs = file_get_contents($root . '/.env');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
		else {
			throw new \Lassi\App\Exception\NotFoundException('No configuration found.');
		}

		// Restore original error handler
		restore_error_handler();

		$configs = explode("\n", trim($configs));
		array_map(function($config) {

			// Remove whitespaces
			$config = preg_replace('(\s+)', '', $config);

			// Add as global vars
			putenv($config);
		}, $configs);
	}
}
