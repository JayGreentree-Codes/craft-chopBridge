<?php
namespace jaygreentreecodes\chopbridge\models;

use craft\base\Model;

class Settings extends Model
{
    public string $subdomain = '';

    /**
     * Changed from defineRules() to rules() for Craft 4/5 compatibility
     */
    public function rules(): array
    {
        return [
            [['subdomain'], 'required'],
            [['subdomain'], 'string'],
        ];
    }
}
