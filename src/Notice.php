<?php declare(strict_types=1);
/**
 * PHP library for handling errors and notices.
 * 
 * @category   JST
 * @package    Notice
 * @subpackage Notice
 * @author     Josantonius - info@josantonius.com
 * @copyright  Copyright (c) 2016 JST PHP Framework
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @version    1.0.0
 * @link       https://github.com/Josantonius/PHP-Notice
 * @since      File available since 1.0.0 - Update: 2016-12-19
 */

namespace Josantonius\Notice;

# use Josantonius\Notice\Exception\NoticeException;

/**
 * Notice handler.
 *
 * @since 1.0.0
 */
class Notice {

    /**
     * Array with list of notices.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $notices;

    /**
     * Default language to display notices.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public static $lang;

    /**
     * Load Jsond file with notices.
     *
     * @since 1.0.0
     *
     * @param string $lang → language
     *
     * @return array → notices
     */
    protected static function load(string $lang = 'en'): array {

        if ($lang !== static::$lang) {

            static::$notices = null;

            static::$lang = $lang;
        }

        if (is_null(static::$notices)) {

            $filepath = __DIR__ . "/resources/notices.jsond";

            $jsonFile = file_get_contents($filepath);

            $notices  = json_decode($jsonFile, true);

            static::$notices = $notices['data'][static::$lang];

            unset($notices);
        }

        return static::$notices;
    }
                                                                                
    /**
     * Get message from the notice code.
     *
     * If the definition does not exist and the class HTTPStatusCode exists,
     * the definition of the HTTPStatusCode class will be used.
	 *
	 * @link https://github.com/Josantonius/PHP-HTTPStatusCode
	 *
     * @since 1.0.0
     *
     * @param string $code → notice code
     *
     * @return string → notice
     */
    public static function get(int $code, string $lang = 'en'): string {

		static::load($lang);

		if (class_exists("HTTPStatusCode") && $code > 99 && $code < 512) {

			if (!isset(static::$notices[$code]) || empty(static::$notices[$code])) {

				return HTTPStatusCode::get($code, static::$lang);
			}
		}

		return static::$notices[$code] ?? "Undefined";
	}

    /**
     * Get a notices array.
     *
     * @since 1.0.0
     *
     * @param string $lang → language
     *
     * @return array → all notices saved
     */
    public static function getAll(string $lang = 'en'): array {

        static::load($lang);

        return static::$notices;
    }
}