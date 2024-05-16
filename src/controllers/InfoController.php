<?php

namespace webdna\systeminfo\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;
use craft\base\PluginInterface;
use yii\base\Module;
use craft\helpers\App;

/**
 * System Info controller
 */
class InfoController extends Controller
{
    public $defaultAction = 'index';
    protected array|int|bool $allowAnonymous = true;

    /**
     * system-info/system-info action
     */
    public function actionIndex(): Response
    {
        if (!App::env('SYSTEM_INFO_KEY') || (Craft::$app->getRequest()->getParam('key') !== App::env('SYSTEM_INFO_KEY'))) {
            return $this->asJson([

            ]);
        }
        
        
        $craft = ['version' => Craft::$app->api->licenseInfo['lastVersion']];
        
        $plugins = Craft::$app->getPlugins()->getAllPlugins();
        $plugins = collect($plugins)->map(function($p){ return [
            'name' => $p->name,
            'version' => $p->version,
        ]; })->toArray();
            
        $modules = [];
        foreach (Craft::$app->getModules() as $id => $module) {
            if ($module instanceof PluginInterface) {
                continue;
            }
            if ($module instanceof Module) {
                $modules[$id] = get_class($module);
            } elseif (is_string($module)) {
                $modules[$id] = $module;
            } elseif (is_array($module) && isset($module['class'])) {
                $modules[$id] = $module['class'];
            } else {
                $modules[$id] = Craft::t('app', 'Unknown type');
            }
        }
        
        $updates = Craft::$app->api->updates;
        //Craft::dd($updates);
            
        $craft['latest'] = $updates['cms']['releases'][0]['version'] ?? $craft['version'];
        
        foreach ($plugins as $key => $plugin) {
            if (isset($updates['plugins'][$key])) {
                $plugins[$key]['latest'] = $updates['plugins'][$key]['releases'][0]['version'] ?? $plugin['version'];
            } else {
                $plugins[$key]['latest'] = $plugin['version'];
            }
        }
        
        return $this->asJson([
            'craft' => $craft,
            'plugins' => $plugins,
            'modules' => $modules,
        ]);
    }
}
