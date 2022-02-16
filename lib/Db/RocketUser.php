<?php

namespace OCA\RocketIntegration\Db;


class RocketUser
{
    protected $databaseName;
    protected $db;

    public function __construct()
    {
        $this->databaseName = 'rocket_users';
        $this->db = \OC::$server->getDatabaseConnection();
    }

    public function getByNcUserId($ncUserId)
    {
        $query = "SELECT * FROM *PREFIX*" . $this->databaseName . " WHERE nc_user_id=? LIMIT 1";
        $result = $this->db->executeQuery($query, [$ncUserId]);
        return $result->fetch();
    }

    public function createRocketUser($ncUserId, $rcUserId, $rcToken)
    {
        $query = "INSERT INTO *PREFIX*" . $this->databaseName . " (nc_user_id, rc_user_id, rc_token) VALUES (?, ?, ?)";
        $result = $this->db->executeQuery($query, [
            $ncUserId,
            $rcUserId,
            $rcToken
        ]);
        return $result;
    }

}