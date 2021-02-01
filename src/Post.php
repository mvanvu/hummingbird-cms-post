<?php

namespace MaiVu\Hummingbird\Plugin\Cms\Post;

use MaiVu\Hummingbird\Lib\Helper\IconSvg;
use MaiVu\Hummingbird\Lib\Helper\Menu;
use MaiVu\Hummingbird\Lib\Helper\Text;
use MaiVu\Hummingbird\Lib\Helper\Uri;
use MaiVu\Hummingbird\Lib\Helper\User;
use MaiVu\Hummingbird\Lib\Mvc\Model\Post as PostItem;
use MaiVu\Hummingbird\Lib\Mvc\Model\PostCategory;
use MaiVu\Hummingbird\Lib\Plugin;

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

	public function registerSystemMenus($source)
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

			$source->systemMenus[IconSvg::render('pencil') . ' ' . Text::_('posts')] = $postMenus;
		}
	}
}