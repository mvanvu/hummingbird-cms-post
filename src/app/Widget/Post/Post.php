<?php

namespace App\Widget;

use App\Helper\Event;
use App\Helper\Service;
use App\Helper\UcmItem;
use App\Mvc\Model\Post as PostModel;
use App\Mvc\Model\PostCategory;
use App\Plugin\Plugin;
use MaiVu\Php\Registry;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class Post extends Widget
{
	public function getContent(): ?string
	{
		$cid      = $this->widget->get('params.categoryIds', [], 'unique');
		$postsNum = $this->widget->get('params.listLimit', 0, 'uint');
		$sortBy   = $this->widget->get('params.sortBy', 'latest');
		$plugin   = Event::getPlugin('Cms', 'Post');

		if ($postsNum < 1)
		{
			$postsNum = $plugin->get('params.listLimit', 15);
		}

		if (empty($sortBy))
		{
			$sortBy = $plugin->get('params.sortBy', 'latest');
		}

		if (count($cid))
		{
			$bindIds = [];
			$nested  = new PostCategory;

			foreach ($cid as $id)
			{
				if ($tree = $nested->getTree((int) $id))
				{
					foreach ($tree as $node)
					{
						$bindIds[] = (int) $node->id;
					}
				}
			}

			if (empty($bindIds))
			{
				return null;
			}

			$queryBuilder = PostModel::query()
				->createBuilder()
				->from(['post' => PostModel::class])
				->where('post.parentId IN ({cid:array})', ['cid' => array_unique($bindIds)])
				->andWhere('post.state = :state: AND post.context = :context:', ['state' => 'P', 'context' => 'post'])
				->orderBy(UcmItem::parseSortBySql($sortBy));

			// Init renderer
			$renderer = $this->getRenderer();
			$partial  = 'Content/' . $this->getPartialId();
			$layout   = $this->widget->get('params.displayLayout', 'FlashNews');

			if ('BlogList' === $layout)
			{
				$paginator = new Paginator(
					[
						'builder' => $queryBuilder,
						'limit'   => $postsNum,
						'page'    => Registry::request()->get('request.page', 1, 'uint'),
					]
				);

				$paginate = $paginator->paginate();

				if ($paginate->getTotalItems())
				{
					return $renderer->getPartial(
						$partial,
						[
							'posts'      => $paginate->getItems(),
							'pagination' => Service::view()->getPartial('Pagination/Pagination',
								[
									'paginator' => $paginator,
								]
							),
						]
					);
				}
			}
			else
			{
				/*if ('FlashNews' === $layout)
				{
					Plugin::addPublicAssets('js/flash-news.js', 'Cms', 'Post');
				}*/

				$posts = $queryBuilder->limit($postsNum, 0)->getQuery()->execute();

				if ($posts->count())
				{
					return $renderer->getPartial($partial, ['posts' => $posts]);
				}
			}
		}

		return null;
	}
}