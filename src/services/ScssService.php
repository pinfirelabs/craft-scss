<?php
/**
 * Scss plugin for Craft CMS 3.x
 *
 * Compiler for SCSS
 *
 * @link      https://chasegiunta.com
 * @copyright Copyright (c) 2018 Chase Giunta
 */

namespace chasegiunta\scss\services;

use chasegiunta\scss\Scss;

use Craft;
use craft\base\Component;

use ScssPhp\ScssPhp\Compiler;

use yii\web\View;

/**
 * ScssService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Chase Giunta
 * @package   Scss
 * @since     1.0.0
 */
class ScssService extends Component
{
    // Public Methods
    // =========================================================================

    public function scss($scss = "", $attributes = "")
    {
        $result = $this->compileScss($scss, $attributes);
        Craft::$app->view->registerCss($result);
    }

    public function compileScss($scss = '', $attributes = '')
    {
        $attributes = unserialize($attributes);

        $settings = Scss::$plugin->getSettings();
        if (Craft::$app->getConfig()->general->devMode) {
            $outputFormat = $settings->devModeOutputFormat;
        } else {
            $outputFormat = $settings->outputFormat;
        }

        $cacheOptions = [
            'cacheDir' => Craft::getAlias('@runtime') . '/scss-cache',
        ];

        $scssphp = new Compiler($cacheOptions);

        if ($attributes['expanded']) {
            $outputFormat = 'Expanded';
        }
        if ($attributes['crunched']) {
            $outputFormat = 'Crunched';
        }
        if ($attributes['compressed']) {
            $outputFormat = 'Compressed';
        }
        if ($attributes['compact']) {
            $outputFormat = 'Compact';
        }
        if ($attributes['nested']) {
            $outputFormat = 'Nested';
        }

        $scssphp->setFormatter("ScssPhp\ScssPhp\Formatter\\$outputFormat");

        $rootPath = Craft::getAlias('@root');
        $scssphp->setImportPaths($rootPath);

        if ($settings->debug) {
            $scssphp->setLineNumberStyle(Compiler::LINE_COMMENTS);
        }

        if ($settings->generateSourceMap) {
            $scssphp->setSourceMap(Compiler::SOURCE_MAP_INLINE);
        }

        $compiled = $scssphp->compile($scss);

        return $compiled;
    }
}
