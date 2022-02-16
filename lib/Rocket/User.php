<?php

namespace OCA\RocketIntegration\Rocket;

use Httpful\Request;
use Httpful\Mime;
use Ramsey\Uuid\Uuid;
use OCA\RocketIntegration\Db\RocketUser as RocketUserDb;
use OCP\IUserSession;
use Psr\Log\LoggerInterface;
use OCA\RocketIntegration\Db\Config;

class User extends Client
{
    /** USER_CREATE 
     * @expects 
     * - R email
     * - R name
     * - R password
     * - R username
     * - O active
     * - O roles
     * - O verified
     * @returns
     * - success
     * - user {
     *      _id
     *      username
     * }
    */
    const CREATE_USER = 'users.create';

    /** CREATE_TOKEN 
     * @expects
     * - R userId or username
     * @returns
     * - success
     * - data {
     *      userId
     *      authToken
     * }
    */
    const CREATE_TOKEN = 'users.createToken';
    const USER_INFO = 'users.info';
    const V1 = 'api/v1/';
    const ROCKET_LOGIN = 'login';

    /** @var IUserSession */
    private $userSession;

    /** @var RocketUserDb */
    private $rocketUserDb;

    /** @var LoggerInterface */
    private $logger;

    /** @var Config */
    private $config;

    public function __construct(IUserSession $userSession, RocketUserDb $rocketUserDb, LoggerInterface $logger, Config $config)
    {
        parent::__construct();
        $this->userSession = $userSession;
        $this->rocketUserDb = $rocketUserDb;
        $this->logger = $logger;
        $this->config = $config;
    }

    public function all()
    {
        try {
            $response = Request::get($this->api . 'users.list')->send();

            if ($response->code == 200 && isset($response->body->success) && $response->body->success == true) {
                return [
                    'status' => 'success',
                    'users' => $response->body->users,
                ];
            }

            return [
                'status' => 'fail',
                'message' => $response->body->error,
            ];
        } catch (\Exception $exception) {
            return [
                'status' => 'fail',
                'message' => 'Connection Error',
            ];
        }
    }

    public function getTokenByUserId($userId)
    {
        return;
    }

    public function createTokenByUserId($userId)
    {
        try {
            $payload = [
                'userId' => $userId,
            ];
            $response = Request::post($this->api . self::CREATE_TOKEN, $payload, Mime::FORM)->send();
            if ($response->code == 200 && isset($response->body->success) && $response->body->success == true) {
                return [
                    'status' => 'success',
                    'token' => $response->body->data->authToken,
                ];
            }
            return [
                'status' => 'error',
                'message' => 'Couldn\'t get token',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function createUser()
    {
        try {
            $user = $this->userSession->getUser();
            $username = $user->getUID();
            $name = $user->getDisplayName();
            $email = $user->getEMailAddress();
            $uuidPassword = Uuid::uuid4()->toString();
            $payload = [
                'username' => $username,
                'name' => $name,
                'password' => $uuidPassword,
                'email' => $email,
                'verified' => true,
            ];
            $response = Request::post($this->api . self::CREATE_USER, $payload, Mime::FORM)->send();
            if ($response->code == 200 && isset($response->body->success) && $response->body->success == true) {
                $_id = $response->body->user->_id;
                $token = $this->createTokenByUserId($_id);
                $this->rocketUserDb->createRocketUser(ncUserId: $username, rcUserId: $_id, rcToken: $token);
                return [
                    'status' => 'success',
                    'userId' => $_id,
                    'token' => $token
                ];
            }
            return [
                'status' => 'error',
                'message' => 'Couldn\'t create user'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getUserInfo($ncUserId)
    {
        $connectionInfo = $this->config->getAdminData();
        $userId = false;
        $authToken = false;
        $rocketUrl = false;
        foreach ($config->getAdminData() as $key => $setting) {
            if ($setting['configkey'] === 'user_id') {
                $userId = $setting['configvalue'];
            }

            if ($setting['configkey'] === 'personal_access_token') {
                $authToken = $setting['configvalue'];
            }
            
            if ($setting['configkey'] === 'rocket_chat_url') {
                $rocketUrl = $setting['configvalue'];
            }
        }
        $userInfoUrl = $rocketUrl . self::V1 . self::USER_INFO . '?' . http_build_query([
            'username' => $ncUserId,
        ]);

        $response = Request::get($userInfoUrl)
            ->addHeaders([
                'X-Auth-Token' => $authToken,
                'X-User-Id' => $userId,
            ])
            ->expectsJson()
            ->send();
        
        if ($response->code == 200) {
            $this->logger->error(json_encode($response->body));
            return $response->body;
        }
        $this->logger->error('ERROR');
        return false;
    }

    public function findByNcUserId($ncUserId)
    {
        $userInDb = $this->rocketUserDb->getByNcUserId($ncUserId);
        if ($userInDb) {
            return $userInDb;
        }
        $userInRocket = $this->getUserInfo($ncUserId);
    }

    public function loginUser($url, $username, $password)
    {
        try {
            $payload = [
                'user' => $username,
                'password' => $password
            ];

            if ($url[-1] != '/') {
                $url .= '/';
            }

            $response = Request::post($url . self::V1 . self::ROCKET_LOGIN, $payload, Mime::FORM)
                ->expectsJson()
                ->send();
            if ($response->code == 200) {
                $userId = $response->body->data->userId;
                $authToken = $response->body->data->authToken;
                $this->config->resetAdminData();
                $this->config->storeAdminData($url, $authToken, $userId);
                return [
                    'status' => 'success',
                    'userId' => $userId,
                    'authToken' => $authToken,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Couldn\'t login user',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
