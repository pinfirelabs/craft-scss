<?php
/**
 * Scss plugin for Craft CMS 3.x
 *
 * @link      https://www.pinfirelabs.com
 * @copyright Copyright (c) 2020 Pinfire Labs
 */

namespace chasegiunta\scss\controllers;

use Craft;
use craft\web\Controller;
use chasegiunta\scss\Scss;

/**
 * Provides a way to build styles dynamically, but still serve them statically.
 *
 * @author    Justin Cherniak
 * @package   Scss
 * @since     2.0.0
 */
class StylesController extends Controller
{
	/**
	 * @var    bool|array Allows anonymous access to this controller's actions.
	 *         The actions must be in 'kebab-case'
	 * @access protected
	 */
	protected $allowAnonymous = ['index'];

	// Public Methods
	// =========================================================================

	/**
	 * @return mixed
	 */
	public function actionIndex()
	{
		$plugin = Scss::$plugin;
		$settings = $plugin->getSettings();
		$twigTemplate = $settings['twig'];

		$scss = Craft::$app->view->renderString($twigTemplate);

		$cacheKey = md5($scss);
		$css = Craft::$app->cache->get($cacheKey);
		if ($css === false) {
			$css = $plugin->scssService->compileScss($scss);

			Craft::$app->cache->set($cacheKey, $css, $settings['cacheTime']);
		}

		header('Content-type: text/css');
		echo $css;
	}
}
