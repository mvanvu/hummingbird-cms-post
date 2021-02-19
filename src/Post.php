<?php

namespace App\Plugin\Cms;

use App\Helper\IconSvg;
use App\Helper\Menu;
use App\Helper\Text;
use App\Helper\Uri;
use App\Helper\User;
use App\Mvc\Model\Post as PostItem;
use App\Mvc\Model\PostCategory;
use App\Plugin\Plugin;

class Post extends Plugin
{
	public static function postRoute(Menu $menuItem)
	{
		$postId = $menuItem->params->get('postId', 0);

		if ($post = PostItem::findFirst((int) $postId))
		{
			return $post->getLink();
		}

		return null;
	}

	public static function categoryRoute(Menu $menuItem)
	{
		$categoryId = $menuItem->params->get('categoryId', 0);

		if ($category = PostCategory::findFirst((int) $categoryId))
		{
			return $category->getLink();
		}

		return null;
	}

	public function onRegisterAdminMenus(&$menus)
	{
		$user = User::getActive();

		if ($user->authorise('post.manage'))
		{
			$postMenus = [
				[
					'title' => IconSvg::render('file-edit') . ' ' . Text::_('posts'),
					'url'   => Uri::route('content/post/index'),
				],
			];

			if ($user->authorise('post.manageCategory'))
			{
				$postMenus[] = [
					'title' => IconSvg::render('albums') . ' ' . Text::_('categories'),
					'url'   => Uri::route('content/post-category/index'),
				];
			}

			if ($user->authorise('post.manageComment'))
			{
				$postMenus[] = [
					'title' => IconSvg::render('bubble') . ' ' . Text::_('comments'),
					'url'   => Uri::route('post/comment/index'),
				];
			}

			if ($user->authorise('post.manageField'))
			{
				$postMenus[] = [
					'title' => IconSvg::render('albums') . ' ' . Text::_('field-groups'),
					'url'   => Uri::route('group-field/post/index'),
				];
				$postMenus[] = [
					'title' => IconSvg::render('field') . ' ' . Text::_('fields'),
					'url'   => Uri::route('field/post/index'),
				];
			}

			$menus = array_merge([
				'post' => [
					'title' => IconSvg::render('pencil') . ' ' . Text::_('posts'),
					'items' => $postMenus,
				],
			], $menus);
		}
	}
}