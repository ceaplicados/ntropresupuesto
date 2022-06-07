<?php
require_once("../dep/interface.php");
$Session->setDateDeath(date("Y-m-d H:i:s"));
setcookie("SessionUID", "", time() - (86400 * 2), "/");
header("Location: /");
exit();
