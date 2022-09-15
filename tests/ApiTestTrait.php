<?php

namespace Tests;
use App\Models\BaseModel;

trait ApiTestTrait
{
    private $response;

    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];


//        \Log::info($actualData);
//        \Log::error($responseData);

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($actualData, $responseData);
    }

    public function assertArrayResponse($response, Array $data)
    {
        $this->assertEquals(200, $response->status());

        $r = json_decode($response->getContent(), true);
        $responseData = $r['data'];

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($data, $responseData);
    }

    public function assertArrayFirstResponse($response, Array $data)
    {
        $this->assertEquals(200, $response->status());

        $r = json_decode($response->getContent(), true);
        $responseData = $r['data'][0];

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($data, $responseData);
    }

    public function assertDataResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];

        $this->assertModelData($actualData, $responseData);
    }

    public function assertApiSuccess()
    {
        if (env('TEST_DEBUG')) {
            $code = $this->response->getStatusCode();
            if ($code != 200) {
                dd($this->response);
            }
        }
        $this->response->assertStatus(200);
        $this->response->assertJson(['success' => true]);
    }

    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($actualData as $key => $value) {
            if (in_array($key, ['created_at', 'updated_at'])) {
                continue;
            }
            if (in_array($key, BaseModel::$number_formats)) {
                $oFormat = number_format($actualData[$key], 2);
                $eFormat = $expectedData[$key];
                if (strpos($eFormat, ',') === false) {
                    $eFormat = number_format($eFormat, 2);
                }
                if ($oFormat !== $eFormat) {
                    dd($key.$oFormat.$eFormat.get_class($this));
                }
                $this->assertEquals($oFormat, $eFormat);
            } else {
                if (!isset($expectedData[$key])) {
                    if (strpos($key, '_formatted') !== false) {
                        continue;
                    }
                    if ($actualData[$key] != false) {
                        dd("$key missing in expected data");
                    }
                    $this->assertTrue($actualData[$key] == false);
                } else {
                    if ($actualData[$key] != $expectedData[$key]){
                        dd(debug_backtrace()[2]['function'] ." =>$key =>". var_export($expectedData[$key], true)." =>$key =>". var_export($actualData[$key], true));
                    }
                    $this->assertEquals($actualData[$key], $expectedData[$key]);
                }
            }
        }
    }

    public function manualAssertEquals($code, $data)
    {
        $this->assertEquals($code, $this->response->status());
        $result = json_decode($this->response->getContent(), true);

        $this->assertEquals($data, $result);
    }

    public function manualAssertContains($code, $data)
    {
        $this->assertEquals($code, $this->response->status());
        $result = json_decode($this->response->getContent(), true);

        $this->manualAssertArrayContains($data, $result);
    }

    public function manualAssertContainsData($code, $data, $notContains = false)
    {
        $this->assertEquals($code, $this->response->status());
        $result = json_decode($this->response->getContent(), true);
        $responseData = $result['data'];

        $this->manualAssertArrayContains($data, $responseData, $notContains);
    }

    public function manualAssertArrayContains(array $checkArray, $baseArray, $notContains = false)
    {
        if (count($baseArray) == 0 || count($checkArray) == 0) {
            return false;
        }
        $result = true;
        //use id match if available to get right array
        //note that all this is assuming primary key field is named id
        $match = $baseArray;
        if (!isset($baseArray['id'])) {
            foreach ($baseArray as $b) {
                if (is_array($b) && isset($b['id']) && $b['id'] == $checkArray['id']) {
                    $match = $b;
                    break;
                }
            }
        }
        foreach ($checkArray as $k => $v) {
            if (in_array($k, ['created_at', 'updated_at'])) {
                continue;
            }
            $b = $notContains == true ? ($checkArray[$k] == @$match[$k]) : ($checkArray[$k] != @$match[$k]);
            if ($b) {
                $result = false;
                break;
            }
        }

        $this->assertEquals(true, $result);
    }
}
