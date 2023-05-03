<?php

use lib\util\Helper;


require_once Helper::getAlias('@lib\app\database\query\IExpression.php');
require_once Helper::getAlias('@lib\app\database\query\Expressions.php');

require_once Helper::getAlias("@common\models\base\interface\IActiveModel.php");
require_once Helper::getAlias("@lib\app\database\Query.php");
require_once Helper::getAlias("@lib\app\database\RelationalQuery.php");


require_once Helper::getAlias('@common\models\base\BaseModel.php');

require_once Helper::getAlias('@common\models\access\Permission.php');
require_once Helper::getAlias('@common\models\access\Grants.php');
require_once Helper::getAlias('@common\models\access\Role.php');
require_once Helper::getAlias('@common\models\access\Restriction.php');
require_once Helper::getAlias('@common\models\access\UserRole.php');
require_once Helper::getAlias('@common\models\user\User.php');


require_once Helper::getAlias('@common\models\assessment\Question.php');
require_once Helper::getAlias('@common\models\assessment\QuestionType.php');
require_once Helper::getAlias("@common\models\assessment\MultipleChoice.php");
