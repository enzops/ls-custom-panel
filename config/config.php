<?php
// SQL CONNECTION

$DB_SERVER    = "192.168.66.3";
$DB_USERNAME  = "rp-panel";
$DB_PASSWORD  = "test";
$DB_NAME      = "rp-panel";
$DB_DSN       = new PDO("mysql:host=$DB_SERVER;dbname=$DB_NAME;charset=utf8", $DB_USERNAME, $DB_PASSWORD);
$DB_LINK      = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

// CHECK CONNECTION TO DB
if ($DB_LINK === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// DEBUG
ini_set('display_errors', 1);
error_reporting(E_ALL);

// VARS
$currentFile = basename($_SERVER['PHP_SELF']);

// FUNCTIONS EXEC
$WebsiteSettings = LoadWebsiteSettings();


// FUNCTIONS

function LoadDataRow($table, $where, $id)
{
  global $DB_SERVER;
  global $DB_USERNAME;
  global $DB_PASSWORD;
  global $DB_NAME;
  global $DB_DSN;

  try {
    $LoadDataRow = $DB_DSN->prepare("SELECT * FROM $table WHERE $where = :id");
    $LoadDataRow->execute(array(':id' => $id));
    $DataRow = $LoadDataRow->fetch();
  } catch (PDOException $e) {
    // Handle the error here
  }

  return $DataRow;
};

function UpdateUserData($id, $token)
{
  global $DB_SERVER;
  global $DB_USERNAME;
  global $DB_PASSWORD;
  global $DB_NAME;
  global $DB_DSN;

  $UpdateUserData = $DB_DSN->prepare("UPDATE users SET token=:token WHERE id=:id");
  $UpdateUserData->bindValue('id', $id);
  $UpdateUserData->bindValue('token', $token);
  $UpdateUserData->execute();
}

// function ScanLangs(){
//   global $LANGS;
//   global $LANGDIR;

//   $LANGDIR  = 'inc/langs/';
//   $LANGS    = array_diff(scandir($LANGDIR), array('..', '.'));
//   $LANGS    = preg_replace("/\.php/", "", $LANGS );
// }

session_start();


function interventiondossier()
{
  global $numticket;
  $bdd = new PDO('mysql:host=192.168.99.3;dbname=rp-panel;charset=utf8', "rp-panel", "test");
  $name = "Jake Smith";
  $numticket = $bdd->prepare('SELECT * FROM personnes WHERE name = :name');
  $numticket->bindValue('name', $name);
  $numticket->execute();
}

function LoadWebsiteSettings()
{
  global $DB_DSN;
  global $WEBSITE_SETTINGS_LANG;
  global $WEBSITE_SETTINGS_NAME;

  $LoadWebsiteSettings = $DB_DSN->prepare("SELECT * FROM settings");
  $LoadWebsiteSettings->execute();
  $WebsiteSettings = $LoadWebsiteSettings->fetchAll();

  $WEBSITE_SETTINGS_LANG = $WebsiteSettings[0]['name'];
  // $WEBSITE_SETTINGS_NAME = $WebsiteSettings[0]['shoptype'];
  // $WEBSITE_SETTINGS_NAME = $WebsiteSettings[0]['lang'];

  // require("inc/langs/$WEBSITE_SETTINGS_LANG.php");
  $WebsiteSettings = $WebsiteSettings[0];
  return $WebsiteSettings;
};

function LoadAllRows($table)
{
  global $AllRows;
  global $DB_SERVER;
  global $DB_USERNAME;
  global $DB_PASSWORD;
  global $DB_NAME;
  global $DB_DSN;

  $LoadAllRows = $DB_DSN->prepare("SELECT * FROM $table");
  $LoadAllRows->execute();
  $AllRows = $LoadAllRows->fetchAll();

  return $AllRows;
}

function LoadAllUsers()
{
  global $AllRows;
  global $DB_SERVER;
  global $DB_USERNAME;
  global $DB_PASSWORD;
  global $DB_NAME;
  global $DB_DSN;

  $LoadAllUsers = $DB_DSN->prepare("SELECT firstName, lastName FROM users");
  $LoadAllUsers->execute();
  $AllRows = $LoadAllUsers->fetchAll();

  return $AllRows;
}

function LimitToAdmins($redirect)
{
  if (!isset($_SESSION['userAdmin']) || $_SESSION['userAdmin'] == false) {
    if (isset($redirect)) {
      header('location: '.$redirect);
    }
    return false;
  } else {
    return true;
  };
}

function LimitToUsers($redirect)
{
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    if (isset($redirect)) {
      header('location: '.$redirect);
    }
    return false;
  } else {
    return true;
  };
}