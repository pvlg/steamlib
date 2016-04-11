<?php

namespace pvlg\steamlib\store\login;

use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;
use pvlg\steamlib\Captcha;
use pvlg\steamlib\community\CommunityTrait;
use pvlg\steamlib\community\profile\Profile;
use pvlg\steamlib\HttpTrait;

/**
 * @property Profile $profile
 */
class Login
{
    use HttpTrait;
    use CommunityTrait;

    public $username = '';
    public $password = '';

    public $donotcache;
    
    public $captchaNeeded;
    public $captchaGid = '-1';
    public $captchaText;

    public function login()
    {
        $this->donotcache = intval(microtime(true) * 1000);

        // getrsakey
        $params = [
            'donotcache' => $this->donotcache,
            'username' => $this->username,
        ];
        $response = $this->http->post('login/getrsakey', [
            'form_params' => $params,
            'ajax' => true,
        ]);
        $rsaJson = json_decode($response->getBody(), true);

        $rsa = new RSA();
        $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        $key = [
            'modulus' => new BigInteger($rsaJson['publickey_mod'], 16),
            'publicExponent' => new BigInteger($rsaJson['publickey_exp'], 16)
        ];
        $rsa->loadKey($key, RSA::PUBLIC_FORMAT_RAW);
        $encryptedPassword = base64_encode($rsa->encrypt($this->password));

        // dologin
        $params = [
            'donotcache' => $this->donotcache,
            'password' => $encryptedPassword,
            'username' => $this->username,
            'twofactorcode' => '',
            'emailauth' => '',
            'loginfriendlyname' => '',
            'captchagid' => $this->captchaGid,
            'captcha_text' => $this->captchaText ? $this->captchaText : '',
            'emailsteamid' => '',
            'rsatimestamp' => $rsaJson['timestamp'],
            'remember_login' => 'true',
        ];
        $response = $this->http->post('login/dologin', [
            'form_params' => $params,
        ]);
        $dologinJson = json_decode($response->getBody(), true);

        if (!$dologinJson['success']) {
            if (isset($dologinJson['captcha_needed']) && $dologinJson['captcha_needed']) {
                $this->captcha_needed = $dologinJson['captcha_needed'];
                $this->captcha_gid = $dologinJson['captcha_gid'];
            } elseif (isset($dologinJson['emailauth_needed']) && $dologinJson['emailauth_needed']) {
                $this->emailauth_needed = $dologinJson['emailauth_needed'];
                $this->emaildomain = $dologinJson['emaildomain'];
                $this->emailsteamid = $dologinJson['emailsteamid'];
            }

            return false;
        }

        return true;
    }

    public function logout()
    {
        return true;
    }

    public function getProfile($profileId = null)
    {
        if ($profileId === null) {
            $profileId = 'current';
        }

        if (!isset($this->_profile[$profileId])) {
            $this->_profile[$profileId] = new Profile();
        }

        return $this->_profile[$profileId];
    }

    public function getCaptchaText()
    {
        $headers = [
            'Connection' => 'keep-alive',
            'Accept' => '*/*',
            'Origin' => 'https://steamcommunity.com',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Language' => 'ru,en-US;q=0.8,en;q=0.6',
        ];
        $params = [
            'gid' => $this->captchaGid,
        ];
        $response = $this->http->get('login/rendercaptcha', [
            'headers' => $headers,
            'query' => $params,
        ]);

        $captcha_base64 = (string)$response->getBody();

        $x = new Captcha();
        $c = [$x, 'recognize'];
        $this->captchaText = call_user_func($c, $captcha_base64);
    }
}
