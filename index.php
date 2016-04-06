<?php
/**
 * Created by PhpStorm.
 * User: kepoly
 * Date: 2/24/2016
 * Time: 6:23 PM
 */

define("WEBSITE_PATH", "http://localhost/personal/casinocoin/");
require_once "src/inc/header.php";
require_once "src/inc/nav.php";
?>

<div ng-view=""></div>

<?php
require_once "src/inc/footer.php";
?>

