<?php

namespace app\commands;

use yii\console\Controller;
use yii\httpclient\Client;
use app\models\Campaign;
use app\models\Publisher;
use app\models\Transaction;

/**
 * This command creates test transactions.
 *
 */
class TestController extends Controller
{
    public $messages = 100000;
    public $timeout = 0;
    public $baseUrl = 'http://localhost:3000/tracker';

    public function options($actionId)
    {
        return ['messages', 'timeout'];
    }

    public function actionIndex()
    {
        $campaigns = Campaign::find()->with('materials')->limit(5)->all();
        $affiliates = Publisher::find()->limit(5)->all();
        $dateFrom = strftime('%F', time() - 3600 * 24);
        $dateTo = strftime('%F', time() + 3600 * 24 * 30);
        $timePeriod = ['after' => strtotime($dateFrom), 'before' => strtotime($dateTo)];
        $cookie = null;

        echo sprintf('Generating transactions between %1$s and %2$s', $dateFrom, $dateTo) . PHP_EOL;

        for ($i = 0; $i < $this->messages; $i++) {
            $affiliate = $affiliates[array_rand($affiliates)];
            $campaign = $campaigns[array_rand($campaigns)];
            $material = $campaign->materials[array_rand($campaign->materials)];

            $reference = 'dt' . rand(1, 10);
            $trackingType = 2;
            $transactionId = rand(1000000, 2000000);
            $transactionAmount = rand(5000, 50000) / 100;
            $email = 'demo@tt.com';
            $descriptionMerchant = 'Demo transaction';
            $descriptionAffiliate = 'Demo transaction';
            $date = date('r', rand($timePeriod['after'], $timePeriod['before']));
            $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8';
            $ip = '10.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254);
            $headers = ['User-Agent' => $userAgent, 'x-tt-ip' => $ip, 'x-tt-time' => $date];
            $cookies = [];
            if ($cookie !== null) {
                $cookies['tts'] = $cookie;
            }
            $randomCounter = rand(1, 1000);

            if ($randomCounter < 20) {
                $data = [
                    't' => Transaction::TYPE_SALE,
                    'cid' => $campaign->id,
                    'mid' => $material->id,
                    'wid' => $affiliate->id,
                    'pid' => $campaign->product_id,
                    'ref' => $reference,
                    'date' => $date,
                    'tt' => 2,
                    'tid' => $transactionId,
                    'tam' => $transactionAmount,
                    'eml' => $email,
                    'dm' => $descriptionMerchant,
                    'da' => $descriptionAffiliate,
                ];
            } else if ($randomCounter < 40) {
                $data = [
                    't' => Transaction::TYPE_LEAD,
                    'cid' => $campaign->id,
                    'mid' => $material->id,
                    'wid' => $affiliate->id,
                    'pid' => $campaign->product_id,
                    'ref' => $reference,
                    'tt' => 2,
                    'tid' => $transactionId,
                    'eml' => $email,
                    'dm' => $descriptionMerchant,
                    'da' => $descriptionAffiliate,
                ];
            } else if ($randomCounter < 60) {
                $data = [
                    't' => Transaction::TYPE_CLICK_OUT,
                    'cid' => $campaign->id,
                    'mid' => $material->id,
                    'wid' => $affiliate->id,
                    'ref' => $reference,
                    's' => 'js',
                ];
            } else if ($randomCounter < 200) {
                $data = [
                    't' => Transaction::TYPE_IMPRESSION,
                    'cid' => $campaign->id,
                    'mid' => $material->id,
                    'wid' => $affiliate->id,
                    'ref' => $reference,
                    's' => 'html'
                ];
            } else {
                $data = [
                    't' => Transaction::TYPE_CLICK,
                    'cid' => $campaign->id,
                    'mid' => $material->id,
                    'wid' => $affiliate->id,
                    'ref' => $reference,
                    's' => 'js',
                ];
            }

            $cookie = $this->sendRequest($data, $headers, $cookies);

            if ($this->timeout > 0) {
                sleep($this->timeout);
            }
        }
    }

    private function sendRequest($data, $headers, $cookies)
    {
        $client = new Client;
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl($this->baseUrl)
            ->setCookies($cookies)
            ->setHeaders($headers)
            ->setData($data)
            ->send();

        if ($response->isOk && !empty($response->cookies->get('tts'))) {
            echo $response->headers['location'] . PHP_EOL;
            return $response->cookies->get('tts');
        }
        
        return null;
    }
}
