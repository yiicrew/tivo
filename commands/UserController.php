<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

/**
 * This command provides conveniences for users.
 */
class UserController extends Controller
{
    /**
     * This command is provided as a utillity to create,update,confirm,delete,block users.
     */
    public function actionIndex()
    {
        $user = new User;
        $user->name = 'Administrator';
        $user->email = 'admin@example.com';
        $user->password_hash = password_hash('admin', PASSWORD_DEFAULT);
        $user->auth_token = md5(time() . 'admin@example.com');
        $user->role = User::ROLE_ADMIN;
        $user->status = User::STATUS_ACTIVE;
        if ($user->save()) {
            echo "Admin user has been created." . PHP_EOL;
        }
    }

    /**
     * This command is used to dump movies table.
     */
    public function actionDump()
    {
        var_dump(Yii::$app->cache->multiGet(['1_campaign_url', '2_campaign_url']));
    }
}
