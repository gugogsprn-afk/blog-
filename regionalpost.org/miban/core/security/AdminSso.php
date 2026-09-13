<?php

/**
 * Cross-admin SSO handoff for EN / RU / FR panels.
 * One login; language switch carries a short-lived signed token.
 */
class AdminSso {

    // Same secret must exist on EN, RU, and FR miban installs.
    const SECRET = 'rp-admin-sso-v1-regionalpost-2026';
    const TTL = 120; // seconds

    public static function mint($username) {
        $username = trim((string)$username);
        if ($username === '') {
            return '';
        }
        $payload = array(
            'u' => $username,
            'e' => time() + self::TTL,
            'n' => self::randomNonce(),
        );
        $body = self::b64(json_encode($payload));
        $sig = hash_hmac('sha256', $body, self::SECRET);
        return $body . '.' . $sig;
    }

    public static function verify($token) {
        $token = trim((string)$token);
        if ($token === '' || strpos($token, '.') === false) {
            return null;
        }
        list($body, $sig) = explode('.', $token, 2);
        $expect = hash_hmac('sha256', $body, self::SECRET);
        if (!hash_equals($expect, $sig)) {
            return null;
        }
        $json = self::ub64($body);
        $payload = json_decode($json, true);
        if (!is_array($payload) || empty($payload['u']) || empty($payload['e'])) {
            return null;
        }
        if (intval($payload['e']) < time()) {
            return null;
        }
        return trim($payload['u']);
    }

    /**
     * Accept ?admin_sso=… on the target admin, establish local session, redirect clean URL.
     */
    public static function acceptIfPresent() {
        $token = isset($_REQUEST['admin_sso']) ? $_REQUEST['admin_sso'] : '';
        if ($token === '') {
            return false;
        }
        $username = self::verify($token);
        if ($username === null) {
            return false;
        }
        $validator = new UserValidator();
        if (!$validator->LoginByUsername($username)) {
            return false;
        }
        $identity = $validator->Create();
        if ($identity) {
            HttpContext::current()->SetIdentity($identity);
        }

        // Drop the one-time token from the URL.
        $params = $_GET;
        unset($params['admin_sso']);
        $qs = http_build_query($params);
        $path = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : 'index.php';
        $target = $path . ($qs !== '' ? ('?' . $qs) : '');
        header('Location: ' . $target);
        exit;
    }

    public static function appendTokenToUrl($url, $username) {
        $token = self::mint($username);
        if ($token === '') {
            return $url;
        }
        $sep = (strpos($url, '?') === false) ? '?' : '&';
        return $url . $sep . 'admin_sso=' . rawurlencode($token);
    }

    private static function b64($raw) {
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    private static function ub64($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private static function randomNonce() {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes(8));
        }
        return md5(uniqid((string)mt_rand(), true));
    }
}
