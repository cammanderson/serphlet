<?php
namespace Serphlet\Util;

/**
 * Locale
 *
 * <p>A Locale object represents a specific geographical, political, or
 * cultural region. An operation that requires a Locale to perform its
 * task is called locale-sensitive and uses the Locale to tailor
 * information for the user.</p>
 * <p>The language argument is a valid ISO Language Code. These codes are the
 * lower-case, two-letter codes as defined by ISO-639. You can find a full list
 * of these codes at a number of sites, such as:
 * {@link http://www.ics.uci.edu/pub/ietf/http/related/iso639.txt
 * http://www.ics.uci.edu/pub/ietf/http/related/iso639.txt}.</p>
 * <p>The country argument is a valid ISO Country Code. These codes are the
 * upper-case, two-letter codes as defined by ISO-3166. You can find a full list
 * of these codes at a number of sites, such as:
 * {@link http://www.chemie.fu-berlin.de/diverse/doc/ISO_3166.html
 * http://www.chemie.fu-berlin.de/diverse/doc/ISO_3166.html}.</p>
 * <p>The variant argument is a vendor or browser-specific code. For example,
 * use WIN for Windows, MAC for Macintosh, and POSIX for POSIX. Where there
 * are two variants, separate them with an underscore, and put the most
 * important one first. For example, a Traditional Spanish collation might
 * construct a locale with parameters for language, country and variant as:
 * "es", "ES", "Traditional_WIN".</p>
 *
 * @author Olivier HENRY <oliv.henry@gmail.com> (PHP5 port of Struts)
 * @author John WILDENAUER <jwilde@users.sourceforge.net> (PHP4 port of Struts)
 * @version $Id$
 */
class Locale {
    /**
     * Locale which represents the English language.
     * @var Locale
     */
    public static $ENGLISH;

    /**
     * Locale which represents the French language.
     * @var Locale
     */
    public static $FRENCH;

    /**
     * Locale which represents the German language.
     * @var Locale
     */
    public static $GERMAN;

    /**
     * Locale which represents the Italian language.
     * @var Locale
     */
    public static $ITALIAN;

    /**
     * Locale which represents the Japanese language.
     * @var Locale
     */
    public static $JAPANESE;

    /**
     * Locale which represents the Korean language.
     * @var Locale
     */
    public static $KOREAN;

    /**
     * Locale which represents the Chinese language.
     * @var Locale
     */
    public static $CHINESE;

    /**
     * Locale which represents the Chinese language as used in China.
     * @var Locale
     */
    public static $SIMPLIFIED_CHINESE;

    /**
     * Locale which represents the Chinese language as used in Taiwan.
     *
     * Same as TAIWAN Locale.
     * @var Locale
     */
    public static $TRADITIONAL_CHINESE;

    /**
     * Locale which represents France.
     * @var Locale
     */
    public static $FRANCE;

    /**
     * Locale which represents Germany.
     * @var Locale
     */
    public static $GERMANY;

    /**
     * Locale which represents Italy.
     * @var Locale
     */
    public static $ITALY;

    /**
     * Locale which represents Japan.
     * @var Locale
     */
    public static $JAPAN;

    /**
     * Locale which represents Korea.
     * @var Locale
     */
    public static $KOREA;

    /**
     * Locale which represents China.
     *
     * Same as SIMPLIFIED_CHINESE Locale.
     * @var Locale
     */
    public static $CHINA;

    /**
     * Locale which represents the People's Republic of China.
     *
     * Same as CHINA Locale.
     * @var Locale
     */
    public static $PRC;

    /**
     * Locale which represents Taiwan.
     *
     * Same as TRADITIONAL_CHINESE Locale.
     * @var Locale
     */
    public static $TAIWAN;

    /**
     * Locale which represents the United Kingdom.
     * @var Locale
     */
    public static $UK;

    /**
     * Locale which represents the United States.
     * @var Locale
     */
    public static $US;

    /**
     * Locale which represents the English speaking portion of Canada.
     * @var Locale
     */
    public static $CANADA;

    /**
     * Locale which represents the French speaking portion of Canada.
     * @var Locale
     */
    public static $CANADA_FRENCH;

    /**
     * The language code, as returned by getLanguage().
     *
     * @var string
     */
    private $language = '';

    /**
     * The country code, as returned by getCountry().
     *
     * @var string
     */
    private $country = '';

    /**
     * The variant code, as returned by getVariant().
     *
     * @var string
     */
    private $variant = '';

    /**
     * The default locale.
     *
     * @var Locale
     */
    private static $defaultLocale = null;

    /**
     * Construct a locale from language, country, variant.
     *
     * @param string $language Lowercase two-letter ISO-639 A2 language code.
     * @param string $country Uppercase two-letter ISO-3166 A2 country code.
     * @param string $variant Vendor and browser specific code.
     * See class description.
     */
    public function __construct($language, $country = '', $variant = '') {
        $this->language = strtolower(trim($language));
        $this->country = strtoupper(trim($country));
        $this->variant = trim($variant);
    }

    /**
     * Returns the default Locale.
     *
     * The default locale is generally once set on start up and then never
     * changed. Normally you should use this locale for everywhere you need
     * a locale. The initial setting matches the default locale, the user has
     * chosen.
     *
     * @return Locale
     */
    public static function getDefault() {
        return self::$defaultLocale;
    }

    /**
     * Changes the default locale.
     *
     * Normally only called on program start up.
     *
     * @param Locale $newLocale The new default locale
     */
    public static function setDefault($newLocale) {
        self::$defaultLocale = $newLocale;
    }

    /**
     * Returns the language code of this locale.
     *
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Returns the country code of this locale.
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Returns the variant code of this locale.
     *
     * @return string
     */
    public function getVariant() {
        return $this->variant;
    }

    /**
     * Gets the string representation of the current locale.
     *
     * This consists of the language, the country, and the variant, separated
     * by an underscore. The variant is listed only if there is a language or
     * country.<br/>
     * Examples : 'en', 'de_DE', '_GB', 'en_US_WIN', 'de__POSIX', 'fr__MAC'
     *
     * @return string
     */
    public function __toString() {
        if ($this->language == '' && $this->country == '')
            return '';
        elseif ($this->country == '' && $this->variant == '') return $this->language;

        $result = $this->language;
        $result .= '_' . $this->country;
        if ($this->variant != '')
            $result .= '_' . $this->variant;

        return $result;
    }

    /**
     * Compares two locales.
     *
     * To be equal, obj must be a Locale with the same language, country
     * and variant code.
     *
     * @param object $obj
     * @return boolean True if obj is equal to this
     */
    public function equals($obj) {
        if (get_class($obj) != '\Serphlet\Util\Locale')
            return false;
        return ($this->language == $obj->getLanguage() && $this->country == $obj->getCountry() && $this->variant == $obj->getVariant());
    }
}

// Setting convenient constants
\Serphlet\Util\Locale::$ENGLISH = new \Serphlet\Util\Locale('en');
\Serphlet\Util\Locale::$FRENCH = new \Serphlet\Util\Locale('fr');
\Serphlet\Util\Locale::$GERMAN = new \Serphlet\Util\Locale('de');
\Serphlet\Util\Locale::$ITALIAN = new \Serphlet\Util\Locale('it');
\Serphlet\Util\Locale::$JAPANESE = new \Serphlet\Util\Locale('ja');
\Serphlet\Util\Locale::$KOREAN = new \Serphlet\Util\Locale('ko');
\Serphlet\Util\Locale::$CHINESE = new \Serphlet\Util\Locale('zh');
\Serphlet\Util\Locale::$SIMPLIFIED_CHINESE = new \Serphlet\Util\Locale('zh', 'CN');
\Serphlet\Util\Locale::$TRADITIONAL_CHINESE = new \Serphlet\Util\Locale('zh', 'TW');
\Serphlet\Util\Locale::$FRANCE = new \Serphlet\Util\Locale('fr', 'FR');
\Serphlet\Util\Locale::$GERMANY = new \Serphlet\Util\Locale('de', 'DE');
\Serphlet\Util\Locale::$ITALY = new \Serphlet\Util\Locale('it', 'IT');
\Serphlet\Util\Locale::$JAPAN = new \Serphlet\Util\Locale('ja', 'JP');
\Serphlet\Util\Locale::$KOREA = new \Serphlet\Util\Locale('ko', 'KR');
\Serphlet\Util\Locale::$CHINA = \Serphlet\Util\Locale::$SIMPLIFIED_CHINESE;
\Serphlet\Util\Locale::$PRC = \Serphlet\Util\Locale::$CHINA;
\Serphlet\Util\Locale::$TAIWAN = \Serphlet\Util\Locale::$TRADITIONAL_CHINESE;
\Serphlet\Util\Locale::$UK = new \Serphlet\Util\Locale('en', 'GB');
\Serphlet\Util\Locale::$US = new \Serphlet\Util\Locale('en', 'US');
\Serphlet\Util\Locale::$CANADA = new \Serphlet\Util\Locale('en', 'CA');
\Serphlet\Util\Locale::$CANADA_FRENCH = new \Serphlet\Util\Locale('fr', 'CA');