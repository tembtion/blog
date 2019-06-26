<?php

return [
    'NO_AVATAR' => 'http://7xkher.com1.z0.glb.clouddn.com/no-avatar.png',
    // 多媒体类型
    'PHOTO_TYPE' => [
        //相册
        'IMAGE_TYPE_PHOTO' => 1,
        //文章
        'IMAGE_TYPE_BLOG' => 2,
        //头像
        'IMAGE_TYPE_AVATAR' => 3,
    ],
    // 文章类型
    'POST_TYPE' => [
        'POST' => 'post',
        'PAGE' => 'page',
    ],
    // 文章状态
    'POST_STATUS' => [
        //已经发表的文章或页面
        'PUBLISH' => ['value' => 'publish', 'name' => '发布', 'edit' => true],
        //文章正在等待审查
        'PENDING' => ['value' => 'pending', 'name' => '等待审查', 'edit' => true],
        //草稿状态
        'DRAFT' => ['value' => 'draft', 'name' => '草稿', 'edit' => true],
        //自动保存的草稿
        'AUTO_DRAFT' => ['value' => 'auto-draft', 'name' => '自动保存的草稿', 'edit' => false],
        //未来的时间发布
        'FUTURE' => ['value' => 'future', 'name' => '未来的时间发布', 'edit' => false],
        //登录后可见
        'PRIVATE' => ['value' => 'private', 'name' => '登录后可见', 'edit' => false],
        //修订
        'INHERIT' => ['value' => 'inherit', 'name' => '修订', 'edit' => false],
        //在回收站中
        'TRASH' => ['value' => 'trash', 'name' => '回收站', 'edit' => true],
    ],
    // 评论状态
    'COMMENT_APPROVED' => [
        //允许
        'ALLOW' => ['value' => '1', 'name' => '允许'],
        //待审
        'MODERATED' => ['value' => 'moderated', 'name' => '待审'],
        //获准
        'APPROVED' => ['value' => 'approved', 'name' => '获准'],
        //垃圾评论
        'SPAM' => ['value' => 'spam', 'name' => '垃圾评论'],
        //回收站
        'TRASH' => ['value' => 'trash', 'name' => '回收站'],
    ],
    'COMMENT_STATUS' => [
        'OPEN' => 'open',
        'CLOSED' => 'closed',
    ],
    // 评论状态
    'PING_STATUS' => [
        'OPEN' => 'open',
        'CLOSED' => 'closed',
    ],
];
