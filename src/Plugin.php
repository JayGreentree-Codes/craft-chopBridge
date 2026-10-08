<?php
namespace jaygreentreecodes\chopbridge;

use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable; // <-- Make sure this line is present!
use jaygreentreecodes\chopbridge\services\ApiService;
use jaygreentreecodes\chopbridge\variables\chopbridgeVariable;
use jaygreentreecodes\chopbridge\models\Settings;
use yii\base\Event; // <-- Make sure this line is present!
use Craft;

/**
 * @property ApiService $apiService
 */
class Plugin extends BasePlugin
{
    // Tell Craft to show the Settings button in the Control Panel
    public bool $hasCpSettings = true;

    public function init()
    {
        parent::init();

        $this->setComponents([
            'apiService' => ApiService::class,
        ]);

        // FIX: Add this event block so Twig recognizes craft.chopbridge!
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                
                // This registers the variable so craft.chopbridge works in Twig
                $variable->set('chopbridge', chopbridgeVariable::class);
            }
        );
    }

    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    // Tell Craft which Twig template to render for the settings page
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'church-online/_settings', 
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
