<?php
namespace jaygreentreecodes\chopbridge\services;

use jaygreentreecodes\chopbridge\Plugin;
use craft\base\Component;
use Craft;

class ApiService extends Component
{

    public function query(string $query): ?array
    {

        $settings = Plugin::getInstance()->getSettings();
        $subdomain = $settings->subdomain ?? '';

        if (empty($subdomain)) {
            Craft::error('Church Online Plugin: Subdomain setting is empty.', __METHOD__);
            return null;
        }

        $endpoint = "https://{$subdomain}.online.church/graphql";

        try {

            $client = Craft::createGuzzleClient();
            
            $response = $client->post($endpoint, [
                'json' => [
                    'query' => $query,
                    'variables' => (object)[],
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10,
            ]);

            $contents = $response->getBody()->getContents();
            $data = json_decode($contents, true);

            return $data;

        } catch (\Exception $e) {
            Craft::error('Church Online API Error: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }
}
