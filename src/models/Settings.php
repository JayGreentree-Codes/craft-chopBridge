<?php
namespace jaygreentreecodes\chopbridge\models;

use craft\base\Model;

class Settings extends Model
{
    public string $subdomain = '';

    public function rules(): array
    {
        return [
            [['subdomain'], 'required'],
            [['subdomain'], 'string'],
        ];
    }
}
