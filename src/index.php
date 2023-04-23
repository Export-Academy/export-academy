<?php

use lib\app\App;
use lib\util\Helper;



require_once 'C:\xampp\htdocs\academy\lib\util\Helper.php';



require_once Helper::getAlias("@lib\app\\view\interface\IViewable.php");


require_once Helper::getAlias('@vendor/scssphp/scssphp/scss.inc.php', "/");


require_once Helper::getAlias('@lib\util\BaseObject.php');


require_once Helper::getAlias('@lib\app\App.php');


require_once Helper::getAlias("@components/Component.php", "/");


// Utility Classes
require_once Helper::getAlias('@lib\util\html\HtmlHelper.php');


// Models
require_once Helper::getAlias('@common\models\base\BaseModel.php');
require_once Helper::getAlias('@common\models\index.php');

// View
require_once Helper::getAlias("@lib\app\\view\View.php");

// Controllers
require_once Helper::getAlias('@common\controller\Controller.php');
require_once Helper::getAlias('@common\controller\BaseController.php');
require_once Helper::getAlias('@web\controller\SourceController.php');
require_once Helper::getAlias("@admin\controller\DashboardController.php");
require_once Helper::getAlias("@admin\controller\UserController.php");


$app = App::instance(['site', 'admin', 'web']);
$app->run();
