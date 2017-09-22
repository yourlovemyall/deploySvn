<?php
use yii\rbac\Item;
 
return [
    //这是你们的管理任务
    'manageThing0' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
    'manageThing1' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
    'manageThing2' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
    'manageThing3' => ['type' => Item::TYPE_OPERATION, 'description' => '...', 'bizRule' => NULL, 'data' => NULL],
 
    //过程中的作用
    'guest' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => NULL,
        'data' => NULL
    ],
 
    'user' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'User',
        'children' => [
            'guest',
            'manageThing0', //用户可以编辑thing0
        ],
        'bizRule' => 'return !Yii::$app->user->isGuest;',
        'data' => NULL
    ],
 
    'moderator' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Moderator',
        'children' => [
            'user',         //user能做的他都可以做
            'manageThing1', //用户可以编辑thing1
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
 
    'admin' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Admin',
        'children' => [
            'moderator',    // 能做moderator可以做的所有东西
            'manageThing2', // 也可以管理 thing2
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
 
    'godmode' => [
        'type' => Item::TYPE_ROLE,
        'description' => 'Super admin',
        'children' => [
            'admin',        // admin能做的都可以做
            'manageThing3', // 也可以管理 thing3
        ],
        'bizRule' => NULL,
        'data' => NULL
    ],
 
];