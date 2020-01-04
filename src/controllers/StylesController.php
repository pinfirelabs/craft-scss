<?php
/**
 * Scss plugin for Craft CMS 3.x
 *
 * @link      https://www.pinfirelabs.com
 * @copyright Copyright (c) 2020 Pinfire Labs
 */

namespace chasegiunta\scss\models;

use Craft;
use craft\web\Controller;

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
		$twigTemplate;
		echo Craft::$app->view->renderString($twigTemplate);
	}
}
