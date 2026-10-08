<?php
namespace jaygreentreecodes\churchonline;

use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable; // <-- Make sure this line is present!
use jaygreentreecodes\churchonline\services\ApiService;
use jaygreentreecodes\churchonline\variables\ChurchOnlineVariable;
use jaygreentreecodes\churchonline\models\Settings;
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

        // FIX: Add this event block so Twig recognizes craft.churchOnline!
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                
                // This registers the variable so craft.churchOnline works in Twig
                $variable->set('churchOnline', ChurchOnlineVariable::class);
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
