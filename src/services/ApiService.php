<?php
namespace jaygreentreecodes\churchonline\services;

use jaygreentreecodes\churchonline\Plugin;
use craft\base\Component;
use Craft;

class ApiService extends Component
{
    /**
     * Executes a GraphQL query against the Church Online Platform API
     *
     * @param string $query
     * @return array|null
     */
    public function query(string $query): ?array
    {
        // 1. Fetch the subdomain directly from your saved plugin settings
        $settings = Plugin::getInstance()->getSettings();
        $subdomain = $settings->subdomain ?? '';

        // 2. Fallback check if the user hasn't configured it in the CP yet
        if (empty($subdomain)) {
            Craft::error('Church Online Plugin: Subdomain setting is empty.', __METHOD__);
            return null;
        }

        // 3. Build the dynamic GraphQL endpoint
        $endpoint = "https://{$subdomain}.online.church/graphql";

        try {
            // 4. Use Craft's built-in Guzzle HTTP client wrapper
            $client = Craft::createGuzzleClient();
            
            $response = $client->post($endpoint, [
                'json' => [
                    'query' => $query,
                    'variables' => (object)[], // FIX: Explicitly forces an empty JSON object "{}" to satisfy ChOP requirements
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10, // Avoid freezing the page load if ChOP goes down
            ]);

            $contents = $response->getBody()->getContents();
            $data = json_decode($contents, true);

            // Return the array payload back to your template variable
            return $data;

        } catch (\Exception $e) {
            // Log any cURL, DNS, or 400 Bad Request errors securely inside Craft logs
            Craft::error('Church Online API Error: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }
}
