<?php

namespace webdna\systeminfo;

use Craft;
use yii\base\Module as BaseModule;
use craft\helpers\UrlHelper;

/**
 * SystemInfo module
 *
 * @method static SystemInfo getInstance()
 */
class SystemInfo extends BaseModule
{
    public function init(): void
    {
        Craft::setAlias('@webdna/systeminfo', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'webdna\\systeminfo\\console\\controllers';
        } else {
            $this->controllerNamespace = 'webdna\\systeminfo\\controllers';
        }

        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
