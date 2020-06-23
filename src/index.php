<?php
require_once realpath(__DIR__) . '/../../../autoload.php';
require_once 'constants.php';

use Prinx\Rejoice\Database;
use Prinx\Utils\Date;
use Prinx\Utils\Str;

echo Str::internationaliseNumber('233 (54 54-66 796');
echo '<br>';

var_dump(preg_match('/^(\+|00)?[0-9-() ]{8,}$/', '00233 (54 54-66 796'));
echo '<br>';

var_dump(preg_match('/^[0-9]+(,[0-9]+)*\.?[0-9]*$/', '1.0000087909'));
echo '<br>';

function betStatuses()
{
    $db = Database::loadAppDBs()['default'];

    $req = $db->prepare("SELECT * FROM bet_statuses");
    $req->execute();

    $statuses = $req->fetchAll();
    echo 'fetchAll = ';
    var_dump($statuses);
    echo '<br>';
    $req->closeCursor();

    return $statuses;
}

function betStatusId($name)
{
    foreach (betStatuses() as $status) {
        if ($status['name'] === $name) {
            return intval($status['id']);
        }
    }

    throw new \Exception('Trying to get an unexistant bet status `' . $name . '`');
}

var_dump(betStatusId('BET_NEW'));
echo '<br>';

var_dump(explode(' ', 2));
echo '<br>';

function createAppNamespace($prefix = '')
{
    echo '<br>' . __FUNCTION__ . '<br>';

    $namespace = Str::pascalCase('default');

    $pos = strpos(
        $namespace,
        $prefix,
        strlen($namespace) - strlen($prefix)
    );

    $not_already_prefixed = $pos === -1 || $pos !== 0;

    if ($not_already_prefixed) {
        $namespace .= $prefix;
    }

    return $namespace;
}

var_dump(createAppNamespace(MENUS_NAMESPACE_PREFIX));

function hasPassed($date, $format = 'd/m/Y')
{
    echo '<br>' . __FUNCTION__ . '<br>';
    return DateTime::createFromFormat($format, $date) < DateTime::createFromFormat($format, date($format));
}

var_dump(hasPassed('7/06/2020'));

echo '<br>' . date('d/m/Y', Date::futureDay(7));

var_dump(parse_ini_file('sample.ini', true, INI_SCANNER_TYPED));
var_dump(['HOST' => 'localhost', 'USER' => 'db_user10', 'DRIVER' => 'mysql']);

$params = [
    'driver' => 'mysql',
    'host' => '173.16.0.8',
    'port' => 3308,
    'user' => 'root',
    'password' => 'rootpwd',
    'dbname' => 'txtgh_infosevo_prince',
];
$dsn = $params['driver'];
$dsn .= ':host=' . $params['host'];
$dsn .= ';port=' . $params['port'];
$dsn .= ';dbname=' . $params['dbname'];

$user = $params['user'];
$pass = $params['password'];

try {
    return new \PDO($dsn, $user, $pass, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,

    ]);
} catch (\PDOException $e) {
    exit('Unable to connect to the database.<br/><br/><span style="color:violet;">Database Parameters</span>:<br/>Driver: "' . $params['driver'] .
        '"<br/>Host: "' . $params['host'] . '"<br/>Port: "' . $params['port'] . '"<br/>Database: "' . $params['dbname'] .
        '"<br/>User: "' . $params['user'] . '"<br/>Password: "' . $params['password'] . '"<br/><br/>Also check if the parameters are correct, if the "<strong>' .
        $params['driver'] . "</strong>\" service is running on the server which host the database and if there is an effective internet cconnection between the server that hosts your appication's server and the server that hosts the database(s)." .
        '<br/><br/><span style="color:red;">ERROR: ' . $e->getMessage() . '</span>');
}
