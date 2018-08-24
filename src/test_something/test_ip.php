<?php

$list = ['10.77.96.2/24', '10.13.4.0/24'];
$r = IpValidator::validateList('127.0.0.1', $list);
var_dump($r);
$r = IpValidator::validateList('10.77.96.1', $list);
var_dump($r);
$r = IpValidator::validateList('10.13.40.24', $list);
var_dump($r);

echo (inet_pton('172.27.1.4'));

class IpValidator
{
    /**
     * Validates the given IP address by the given pattern.
     *
     * @param string $ip      The IP address to validate.
     * @param string $pattern The pattern which validates the IP address.
     *
     * @return boolean
     */
    public static function validate($ip, $pattern)
    {
        $method = "validate" . ucfirst(self::getIpType($ip));

        return call_user_func_array(['self', $method], [$ip, $pattern]);
    }

    /**
     * Validates the given IP address by the given pattern list.
     *
     * @param string $ip   The IP address to validate.
     * @param array  $list The pattern list which validates the IP address.
     *
     * @return boolean
     */
    public static function validateList($ip, $list)
    {
        $valid  = false;
        $method = "validate" . ucfirst(self::getIpType($ip));
        foreach ($list as $pattern) {
            $valid = $valid || call_user_func_array(['self', $method], [$ip, $pattern]);
        }

        return $valid;
    }

    /**
     * Checks the given IP pattern for errors.
     *
     * @param string $pattern A IP pettern to check.
     *
     * @return boolean
     */
    public static function checkPattern($pattern)
    {
        $method = "checkPattern" . self::getPatternType($pattern);

        return call_user_func_array(['self', $method], [$pattern]);
    }

    /**
     * Return the type of given IP address.
     *
     * @param string $ip The IP address to check.
     *
     * @return string Ipv4 or Ipv6
     * @throws Exception
     */
    private static function getIpType($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new Exception("The given {$ip} is not valid IP address");
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'Ipv6' : 'Ipv4';
    }

    /**
     * Returns the type of given IP pattern.
     *
     * @param string $pattern The IP validation pattern.
     *
     * @return string Wildcard or Range or Netmask
     * @throws Exception
     */
    private static function getPatternType($pattern)
    {
        $array = ['-' => 'Range', '/' => 'Netmask', '.' => 'Wildcard'];
        foreach ($array as $key => $value) {
            if (preg_match("#{$key}#", $pattern)) {
                return $value;
            }
        }
        throw new Exception("There is no matching validation method for this pattern: {$pattern}");
    }

    private static function validateIpv4($ip, $pattern)
    {
        $method = "validate" . self::getPatternType($pattern) . "Ipv4";

        return call_user_func_array(['self', $method], [$ip, $pattern]);
    }

    private static function validateIpv6($ip, $pattern)
    {
        $method = "validate" . self::getPatternType($pattern) . "Ipv6";

        return call_user_func_array(['self', $method], [$ip, $pattern]);
    }

    private static function validateWildcardIpv4($ip, $pattern)
    {
        $pattern = rtrim($pattern, ".");
        $pattern .= str_repeat(".%", 3 - preg_match_all("/[.]/", $pattern));
        $pattern = str_replace(['*'], '%', $pattern);
        $regExp  = str_replace('%', '\d{1,3}', preg_quote(trim($pattern), '/'));

        return (boolean)preg_match("/^{$regExp}$/", $ip);
    }

    private static function validateWildcardIpv6($ip, $pattern)
    {
        if (in_array($pattern, ['*', '%'])) {
            return true;
        }

        return ($ip == $pattern);
    }

    private static function validateRangeIpv4($ip, $pattern)
    {
        $range = explode('-', $pattern);
        if (!filter_var($range[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && preg_match("/^\d+$/", $range[1])) {
            $range[1] = substr($range[0], 0, strrpos($range[0], '.') + 1) . $range[1];
        }

        return self::validateRangeIpvBoth($ip, "{$range[0]}-{$range[1]}");
    }

    private static function validateRangeIpv6($ip, $pattern)
    {
        return self::validateRangeIpvBoth($ip, $pattern);
    }

    private static function validateRangeIpvBoth($ip, $pattern)
    {
        $ip_pton = filter_var($ip, FILTER_VALIDATE_IP) ? inet_pton($ip) : false;
        if (!self::checkPattern($pattern)) {
            throw new Exception("The {$pattern} IP range is invalid");
        }
        list($lower, $upper) = explode('-', $pattern);
        $lower_pton = isset($lower) && filter_var($lower, FILTER_VALIDATE_IP) ? inet_pton($lower) : false;
        $upper_pton = isset($upper) && filter_var($upper, FILTER_VALIDATE_IP) ? inet_pton($upper) : false;

        return ((strcmp($lower_pton, $ip_pton) <= 0) && (strcmp($ip_pton, $upper_pton) <= 0));
    }

    private static function validateNetmaskIpv4($ip, $pattern)
    {
        return self::validateNetmaskIpvBoth($ip, $pattern);
    }

    private static function validateNetmaskIpv6($ip, $pattern)
    {
        return self::validateNetmaskIpvBoth($ip, $pattern);
    }

    private static function validateNetmaskIpvBoth($ip, $pattern)
    {
        if (!self::checkPattern($pattern)) {
            throw new Exception("The given {$pattern} CIDR is invalid");
        }
        list($net, $netmask) = explode("/", $pattern);
        $binaryIp  = self::inetToBits(inet_pton($ip));
        $binaryNet = self::inetToBits(inet_pton($net));
        $ipBits    = substr($binaryIp, 0, $netmask);
        $netBits   = substr($binaryNet, 0, $netmask);

        return ($ipBits === $netBits);
    }

    private static function checkPatternWildcard($pattern)
    {
        if (filter_var($pattern, FILTER_VALIDATE_IP)) {
            return true;
        }
        $pattern = rtrim($pattern, ".");
        $count   = preg_match_all("/[.]/", $pattern);
        if (($count < 2) && !in_array($pattern, ['*', '%'])) {
            return false;
        }
        $pattern .= str_repeat(".%", 3 - $count);
        $pattern = str_replace(['*', '%'], '0', $pattern);

        return (boolean)filter_var($pattern, FILTER_VALIDATE_IP);
    }

    private static function checkPatternRange($pattern)
    {
        $_ = explode("-", $pattern);
        if (!filter_var($_[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && preg_match("/^\d+$/", $_[1])) {
            $_[1] = substr($_[0], 0, strrpos($_[0], '.') + 1) . $_[1];
        }

        return (filter_var($_[0], FILTER_VALIDATE_IP) && filter_var($_[1], FILTER_VALIDATE_IP));
    }

    private static function checkPatternNetmask($pattern)
    {
        $_ = explode("/", $pattern);

        return (isset($_[0]) && isset($_[1]) && filter_var($_[0], FILTER_VALIDATE_IP) && ($_[1] <= (self::getIpType($_[0]) == 'Ipv4' ? 32 : 128)));
    }

    private static function inetToBits($inet)
    {
        $unpacked = unpack('A*', $inet);
        $unpacked = str_split($unpacked[1]);
        $binaryip = '';
        foreach ($unpacked as $char) {
            $binaryip .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        return $binaryip;
    }
}