<?php 
class User {
    /**
     * Identify the browser based on the user agent.
     * 
     * @return string The name of the browser (e.g., Chrome, Firefox, Safari).
     */
    public function getBrowser() {
        $Arr = [
            '/SamsungBrowser/i' => 'Samsung Browser',
            '/Edg/i' => 'Microsoft Edge',
            '/OPX/i' => 'Opera GX',
            '/OPR/i' => 'Opera',
            '/UCBrowser/i' => 'UC Browser',
            '/CriOS/i' => 'Google Chrome (iOS)',
            '/FxiOS/i' => 'Mozilla Firefox (iOS)',
            '/Firefox/i' => 'Mozilla Firefox',
            '/QQBrowser/i' => 'QQ Browser',
            '/MiuiBrowser/i' => 'Mi Browser',
            '/PHX/i' => 'Phoenix Browser',
            '/Chrome/i' => 'Google Chrome',
            '/Safari/i' => 'Apple Safari'
        ];

        foreach ($Arr as $X => $value) {
            if (preg_match($X, $this->userAgent())) {
                $Browser = $value;
                break;
            }
        }

        return $Browser;
    }

    /**
     * Get the user agent string of the user.
     * 
     * @return string The User-Agent header sent by the browser.
     */
    public function userAgent() {
        return ($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * Get the current IP address of the user.
     * 
     * @return string The user's public IP address.
     */
    public function ipAddress() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $IPAddress = end(array_filter(array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        } else {
            $IPAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $IPAddress;
    }

    /**
     * Identify the operating system based on the user agent.
     * 
     * @return string The name of the operating system (e.g., Windows, Android, Linux).
     */
    public function getOS() {
        if (preg_match('/Windows NT 10/i', $this->userAgent())) {
            if (preg_match('/Chrome\/(\d+)\./i', $this->userAgent(), $version)) {
                $XVersion = (int)$version[1];
                if ($XVersion >= 131) return 'Windows 11';
                elseif ($XVersion === 130) return 'Windows 10';
            }
        }

        $OSArr = [
            '/Windows NT 6.3/i' => 'Windows 8.1',
            '/Windows NT 6.2/i' => 'Windows 8',
            '/Windows NT 6.1/i' => 'Windows 7',
            '/Windows NT 6.0/i' => 'Windows Vista',
            '/Windows NT 5.2/i' => 'Windows Server 2003/XP x64',
            '/Windows NT 5.1/i' => 'Windows XP',
            '/Windows XP/i' => 'Windows XP',
            '/Windows Phone/i' => 'Windows Phone',
            '/PlayStation 4/i' => 'PlayStation 4',
            '/PlayStation 5/i' => 'PlayStation 5',
            '/Mac OS/i' => 'Mac OS',
            '/iPhone/i' => 'iPhone',
            '/iPad/i' => 'iPad',
            '/Android/i' => 'Android',
            '/Ubuntu/i' => 'Linux Ubuntu',
            '/Linux/i' => 'Linux OS',
            '/Googlebot/i' => 'Google Bot',
            '/bingbot/i' => 'Bing Bot',
            '/Yahoo!/i' => 'Yahoo! Bot',
        ];

        foreach ($OSArr as $regex => $os) {
            if (preg_match($regex, $this->userAgent())) {
                return $os;
            }
        }
        return 'Unknown OS';
    }

    /**
     * Get the reverse DNS (hostname) of the user's IP address.
     * 
     * @return string The reverse DNS hostname of the IP.
     */
    public function getReverseDNS() {
        return gethostbyaddr($this->ipAddress());
    }

   /**
     * Fetch user information using the IP geolocation API.
     * 
     * @return object Decoded JSON data containing user information.
     */
    public function fetchUser() {
        $APIRequestURL = 'https://api.ipgeolocation.io/ipgeo?apiKey=API_KEY&ip=' . $this->ipAddress();
        return json_decode(file_get_contents($APIRequestURL), true);
    }

    public function userCountry() {
        $userData = $this->fetchUser();
        return (isset($userData['country_name']) ? $userData['country_name'] : 'Unknown Country');
    }

    public function userCountryOfficial() {
        $userData = $this->fetchUser();
        return (isset($userData['country_name_official']) ? $userData['country_name_official'] : 'Unknown Country');
    }

    public function countryContinent() {
        $userData = $this->fetchUser();
        return (isset($userData['continent_name']) ? $userData['continent_name'] : 'Unknown Continent');
    }

    public function countryContinentCode() {
        $userData = $this->fetchUser();
        return (isset($userData['continent_code']) ? $userData['continent_code'] : 'Unknown Continent Code');
    }

    public function countryCode() {
        $userData = $this->fetchUser();
        return (isset($userData['country_code2']) ? $userData['country_code2'] : 'Unknown Country Code');
    }

    public function countryFlag() {
        return 'https://flagcdn.com/' . strtolower($this->countryCode()) . '.svg';
    }

    public function isEuropeanCountry() {
        $userData = $this->fetchUser();
        return (isset($userData['is_eu']) ? ($userData['is_eu'] ? 'True' : 'False') : 'Unknown Country');
    }

    public function countryCapital() {
        $userData = $this->fetchUser();
        return (isset($userData['country_capital']) ? $userData['country_capital'] : 'Unknown Capital');
    }

    public function countryState() {
        $userData = $this->fetchUser();
        return (isset($userData['state_prov']) ? $userData['state_prov'] : 'Unknown Country State');
    }

    public function countryStateCode() {
        $userData = $this->fetchUser();
        return (isset($userData['state_code']) ? $userData['state_code'] : 'Unknown State Code');
    }

    public function getISP() {
        $userData = $this->fetchUser();
        return (isset($userData['isp']) ? $userData['isp'] : 'Unknown ISP');
    }

    public function countryTimezone() {
        $userData = $this->fetchUser();
        return (isset($userData['time_zone']) ? $userData['time_zone']['name'] : 'Unknown ISP');
    }

    public function internetOrganization() {
        $userData = $this->fetchUser();
        return (isset($userData['organization']) ? $userData['organization'] : 'Unknown ISP');
    }

    public function countryTime() {
        $userData = $this->fetchUser();
        return (isset($userData['time_zone']) ? $userData['time_zone']['current_time'] : 'Unknown ISP');
    }

    public function countryCallingCode() {
        $userData = $this->fetchUser();
        return (isset($userData['calling_code']) ? $userData['calling_code'] : 'Unknown ISP');
    }

    public function countryTLD() {
        $userData = $this->fetchUser();
        return (isset($userData['country_tld']) ? $userData['country_tld'] : 'Unknown ISP');
    }

    public function countryDistrict() {
        $userData = $this->fetchUser();
        return (isset($userData['district']) ? $userData['district'] : 'Unknown ISP');
    }

    public function countryCurrency() {
        $userData = $this->fetchUser();
        return (isset($userData['currency']) ? $userData['currency']['name'] : 'Unknown ISP');
    }

    public function countryCurrencyCode() {
        $userData = $this->fetchUser();
        return (isset($userData['currency']) ? $userData['currency']['code'] : 'Unknown ISP');
    }

    public function countryCurrencySymbol() {
        $userData = $this->fetchUser();
        return (isset($userData['currency']) ? $userData['currency']['symbol'] : 'Unknown ISP');
    }
}
?>
