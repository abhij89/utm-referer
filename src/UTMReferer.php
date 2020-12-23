<?php

namespace Abhij89\UTMReferer;

use Illuminate\Contracts\Cookie;
use Illuminate\Http\Request;
use Abhij89\UTMReferer\Exceptions\InvalidConfiguration;

class UTMReferer {

    /** @var string */
    protected $refererCookieKey;
    
    /** @var string */
    protected $utmCookieKey;

    /** @var array */
    protected $sources;

    /** @var \Illuminate\Contracts\Cookie\Factory */
    protected $cookie;

    public function __construct(?string $refererCookieKey, ?string $utmCookieKey, array $sources, Cookie\Factory $cookie) {
	if (empty($refererCookieKey) && empty($utmCookieKey)) {
	    throw InvalidConfiguration::emptyCookieKey();
	}

	$this->refererCookieKey = $refererCookieKey;
	$this->utmCookieKey = $utmCookieKey;
	$this->sources = $sources;
	$this->session = $cookie;
    }

    public function get(): string {
	return $this->cookie->get($this->sessionKey, '');
    }

    public function forget() {
	$this->session->forget($this->sessionKey);
    }

    public function put(string $referer) {
	return $this->session->put($this->sessionKey, $referer);
    }

    public function putFromRequest(Request $request) {
	$referer = $this->determineFromRequest($request);

	if (!empty($referer)) {
	    $this->put($referer);
	}
    }

    protected function determineFromRequest(Request $request): string {
	foreach ($this->sources as $source) {
	    if ($referer = (new $source)->getReferer($request)) {
		return $referer;
	    }
	}

	return '';
    }

    protected function saveUTMs(String $sName) {
	$host = $_SERVER['HTTP_HOST'];
	preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
	if (!empty($matches[0])) {
	    $hostName = $matches[0];
	} else {
	    $hostName = $_SERVER['HTTP_HOST'];
	}

	if (!empty($_SERVER['HTTP_REFERER'])) {
	    $cookie_name = "user-referer";

	    $refererName = "";
	    $referer = parse_url($_SERVER['HTTP_REFERER']);
	    $refererDomain = isset($referer['host']) ? $referer['host'] : $referer['path'];
	    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $refererDomain, $regs)) {
		$refererName = $regs['domain'];
	    }

	    if (!isset($_COOKIE[$cookie_name]) && !empty($_SERVER['HTTP_REFERER']) && ($refererName != $hostName)) {
		$cookie_value = $_SERVER['HTTP_REFERER'];
		setcookie($cookie_name, $cookie_value, time() + (86400 * 90), "/", "." . $hostName);
	    }
	}

	$utm_params = [];

	if (!empty($_GET['utm_source'])) {
	    $utm_params['utm_source'] = $_GET['utm_source'];
	}
	if (!empty($_GET['utm_medium'])) {
	    $utm_params['utm_medium'] = $_GET['utm_medium'];
	}
	if (!empty($_GET['utm_campaign'])) {
	    $utm_params['utm_campaign'] = $_GET['utm_campaign'];
	}
	if (!empty($_GET['utm_term'])) {
	    $utm_params['utm_term'] = $_GET['utm_term'];
	}
	if (!empty($_GET['utm_content'])) {
	    $utm_params['utm_content'] = $_GET['utm_content'];
	}

	$utm_cookie_name = "user-utm";
	if (!empty($utm_params)) {
	    $utm_encoded = json_encode($utm_params);
	    setcookie($utm_cookie_name, $utm_encoded, time() + (86400 * 90), "/", "." . $hostName);
	}
    }
}
