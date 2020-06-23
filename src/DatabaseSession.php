<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Rejoice;

require_once 'constants.php';
require_once 'Database.php';
require_once 'Session.php';
require_once 'SessionInterface.php';

// use Session;
// use SessionInterface;
/**
 * Handle the USSD Session: save and retrieve the session data from the database
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class DatabaseSession extends Session implements SessionInterface
{
    protected $db;
    protected $tableName;
    protected $tableNameSuffix = '_ussd_sessions';

    public function __construct($app)
    {
        parent::__construct($app);

        $this->tableName = strtolower($app->id()) . $this->tableNameSuffix;

        $this->loadDB();

        if ($app->params('environment') !== PROD) {
            $this->createSessionTableIfNotExists();
        }

        $this->start();
    }

    public function loadDB()
    {
        $this->db = Database::loadSessionDB();
    }

    private function createSessionTableIfNotExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `$this->tableName`(
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `msisdn` VARCHAR(20) NOT NULL,
                  `session_id` VARCHAR(50) NOT NULL,
                  `ddate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                  `session_data` TEXT,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `NewIndex1` (`msisdn`),
                  UNIQUE KEY `NewIndex2` (`session_id`)
                ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";

        $result = $this->db->query($sql);
        $result->closeCursor();
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->tableName WHERE msisdn = :msisdn";
        $result = $this->db->prepare($sql);
        $result->execute(['msisdn' => $this->msisdn]);
        $result->closeCursor();
    }

    public function hardReset()
    {
        $sql = "UPDATE $this->tableName SET session_data=null WHERE msisdn = :msisdn";
        $result = $this->db->prepare($sql);
        $result->execute(['msisdn' => $this->msisdn]);
        $result->closeCursor();
    }

    public function retrievePreviousData()
    {
        $data = $this->retrieveData();

        if (!empty($data)) {
            $this->updateId();
        }

        return $data;
    }

    public function retrieveData()
    {
        $sql = "SELECT (session_data) FROM $this->tableName WHERE msisdn = :msisdn";

        $req = $this->db->prepare($sql);
        $req->execute(['msisdn' => $this->msisdn]);
        $result = $req->fetchAll(\PDO::FETCH_ASSOC);
        $req->closeCursor();

        if (empty($result)) {
            return [];
        }

        $sessionData = $result[0]['session_data'];

        return $sessionData !== '' ? json_decode($sessionData, true) : [];
    }

    public function updateId()
    {
        $req = $this->db
            ->prepare("UPDATE $this->tableName SET session_id = :session_id WHERE msisdn = :msisdn");

        $req->execute([
            'session_id' => $this->id,
            'msisdn' => $this->msisdn,
        ]);

        return $req->closeCursor();
    }

    public function previousSessionNotExists()
    {
        $sql = "SELECT COUNT(*) FROM $this->tableName WHERE msisdn = :msisdn";
        $result = $this->db->prepare($sql);
        $result->execute(['msisdn' => $this->msisdn]);

        $nb_rows = (int) $result->fetchColumn();

        $result->closeCursor();

        return $nb_rows <= 0;
    }

    public function save()
    {
        $data = $this->data;

        if ($this->previousSessionNotExists()) {
            return $this->createDataRecord($data);
        }

        return $this->updateDataRecord($data);
    }

    public function createDataRecord($data)
    {
        $sql = "INSERT INTO $this->tableName (session_data, msisdn, session_id) VALUES (:session_data, :msisdn, :session_id)";

        $result = $this->db->prepare($sql);
        $result->execute([
            'session_data' => json_encode($data),
            'msisdn' => $this->msisdn,
            'session_id' => $this->id,
        ]);

        return $result->closeCursor();
    }

    public function updateDataRecord($data)
    {
        $sql = "UPDATE $this->tableName SET session_data = :session_data WHERE msisdn = :msisdn";

        $result = $this->db->prepare($sql);

        $result->execute([
            'session_data' => json_encode($data),
            'msisdn' => $this->msisdn,
        ]);

        return $result->closeCursor();
    }
}
