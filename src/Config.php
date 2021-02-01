<?php

use MaiVu\Hummingbird\Plugin\Cms\Post\Post;

return [
	'name'        => 'Post',
	'group'       => 'Cms',
	'version'     => '1.0.0',
	'title'       => 'post-plugin-title',
	'description' => 'post-plugin-desc',
	'author'      => 'Mai Vu',
	'authorEmail' => 'maivubc@gmail.com',
	'authorUrl'   => 'https://github.com/mvanvu',
	'updateUrl'   => null,
	'permissions' => [
		'create'         => 'core-create',
		'edit'           => 'core-edit',
		'editState'      => 'core-edit-state',
		'delete'         => 'core-delete',
		'manageCategory' => 'core-manage-categories',
		'manageComment'  => 'core-manage-comments',
		'manageField'    => 'core-manage-fields',
		'manageOwn'      => 'core-manage-own',
	],
	'menus'       => [
		'post'          => [
			'route'  => Post::class . '::postRoute',
			'params' => [
				[
					'name'     => 'postId',
					'type'     => 'CmsModalUcmItem',
					'context'  => 'post',
					'required' => true,
					'filters'  => ['uint'],
					'rules'    => ['ValidUcmItem'],
					'messages' => [
						'ValidUcmItem' => 'post-not-found',
					],
				],
			],
		],
		'post-category' => [
			'route'  => Post::class . '::categoryRoute',
			'params' => [
				[
					'name'      => 'categoryId',
					'type'      => 'CmsModalUcmItem',
					'context'   => 'post-category',
					'filters'   => ['uint'],
					'rules'     => ['ValidUcmItem'],
					'translate' => true,
					'messages'  => [
						'ValidUcmItem' => 'category-not-found',
					],
				],
			],
		],
	],
];
