<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views;

use Application\Models\Person;

abstract class BaseView
{
    protected $twig;
    public $outputFormat;
    public $vars = [
        'APPLICATION_NAME' => APPLICATION_NAME,
        'BASE_URL'         => BASE_URL,
        'BASE_URI'         => BASE_URI,
    ];

    abstract public function render();

    public function __construct(array $data=null)
    {
        if (count($data)) { foreach ($data as $k=>$v) { $this->vars[$k] = $v; } }

        $locale = LOCALE.'.utf8';
        $this->vars['lang'] = strtolower(substr(LOCALE, 0, 2));

        putenv("LC_ALL=$locale");
        setlocale(LC_ALL, $locale);
        bindtextdomain('labels',   APPLICATION_HOME.'/language');
        bindtextdomain('errors',   APPLICATION_HOME.'/language');
        bindtextdomain('messages', APPLICATION_HOME.'/language');
        textdomain('labels');

        #$this->outputFormat = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';

        $templateLocations = [];
        if (defined('THEME')) {
            $dir  = SITE_HOME.'/Themes/'.THEME;
            $twig = $dir.'/twig';
            $conf = $dir.'/theme_config.inc';
            
            if (is_dir($twig)) {
                $templateLocations[] = $twig;
            }
            if (is_file($conf)) {
                $this->vars['THEME'] = include $conf;
            }
        }
        $templateLocations[] = APPLICATION_HOME.'/twig';
        $loader = new \Twig_Loader_Filesystem($templateLocations);

        #$this->twig = new \Twig_Environment($loader, ['cache' => SITE_HOME.'/twig']);
        $this->twig = new \Twig_Environment($loader, [
            'cache'            => false,
            'strict_variables' => true,
            'debug'            => true
        ]);
        $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addFunction (new \Twig_SimpleFunction('_', [$this, '_']));
        $this->twig->addFunction (new \Twig_SimpleFunction('url',       function ($route_name, $params=[]  ) { return self::generateUrl($route_name, $params); }));
        $this->twig->addFunction (new \Twig_SimpleFunction('uri',       function ($route_name, $params=[]  ) { return self::generateUri($route_name, $params); }));
        $this->twig->addFunction (new \Twig_SimpleFunction('isAllowed', function ($resource,   $action=null) { return Person::isAllowed($resource, $action); }));

        $this->vars['utilityBar'] = [];
        if (isset($_SESSION['USER'])) {
            $this->vars['utilityBar'][] = [
                'label' => $_SESSION['USER']->getFullname(),
                'id'    => 'User_menu',
                'links' => [
                    ['url' => self::generateUri('login.logout'), 'label'=>$this->_('logout')]
                ]
            ];
        }

        $links  = [];
        $routes = [
            'people' => $this->_(['person', 'people', 4]),
            'users'  => $this->_(['user',   'users',  4])
        ];
        foreach ($routes as $controller=>$label) {
            if (Person::isAllowed($controller, 'index')) {
                $links[] = ['url'=>self::generateUri("$controller.index"), 'label'=>$label];
            }
        }
        if ($links) {
            $this->vars['utilityBar'][] = [
                'label'=> $this->_('admin'),
                'id'   => 'Administrator_menu',
                'links' => $links
            ];
        }
    }

    /**
     * Returns the gettext translation of msgid
     *
     * The default domain is "labels".  Any other text domains must be passed
     * in the second parameter.
     *
     * For entries in the PO that are plurals, you must pass msgid as an array
     * $this->translate( ['msgid', 'msgid_plural', $num] )
     *
     * @param mixed   $msgid  String or Array
     * @param string  $domain Alternate domain
     * @return string
     */
    public function _($msgid, $domain=null)
    {
        if (is_array($msgid)) {
            return $domain
                ? dngettext($domain, $msgid[0], $msgid[1], $msgid[2])
                : ngettext (         $msgid[0], $msgid[1], $msgid[2]);
        }
        else {
            return $domain
                ? dgettext($domain, $msgid)
                : gettext (         $msgid);
        }
    }

    /**
     * Alias of $this->_()
     */
    public function translate($msgid, $domain=null)
    {
        return $this->_($msgid, $domain);
    }
    /**
     * Creates a URI for a named route
     *
     * This imports the $ROUTES global variable and calls the
     * generate function on it.
     *
     * @see https://github.com/auraphp/Aura.Router/tree/2.x
     * @param string $route_name
     * @param array $params
     * @return string
     */
    public static function generateUri($route_name, $params=[])
    {
        global $ROUTES;
        return $ROUTES->generate($route_name, $params);
    }
    public static function generateUrl($route_name, $params=[])
    {
        return "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]".self::generateUri($route_name, $params);
    }
}
