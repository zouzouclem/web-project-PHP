<?php
include_once(__DIR__ . '/../config.php');

include_once(APP_ROOT . "/menu.php");
include_once(APP_ROOT . "/admin/memberAccess.php");
include_once(APP_ROOT . "/userInput.php");

if(!isAdmin())
{
    redirectToErrorPage();
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../projet.css" media="all" type="text/css" /></head>
<body>

<?php
    $memberId = (int) clean_input($_GET["memberId"]);
    if(!isset($memberId) || $memberId == 0)
        exit;

    $member = getMember($memberId);
    $isBanned = $member['isBanned'];
    if($isBanned)
        unbanMember($memberId);
    else
        banMember($memberId);

    // redirect to user admin view
    header("Location: " . "userAdministrationView.php");

?>
</body>