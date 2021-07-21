<?php

declare(strict_types=1);

namespace Tests\Traits;

use Exception;
use Illuminate\Testing\TestResponse;

trait TestSaves
{

    protected function assertStore(
        array $sendData,
        array $testDatase,
        array $testJsonData = null
    ): TestResponse {

        $response = $this->json('POST', $this->routeStore(), $sendData);

        if ($response->status() !== 201) {
            throw new Exception("Response must be 201, given {$response->status()} : \n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatase);
        $this->assertJsonResponseContent($response, $testDatase, $testJsonData);


        return $response;
    }

    protected function assertUpdate(
        array $sendData,
        array $testDatase,
        array $testJsonData = null
    ): TestResponse {

        $response = $this->json('PUT', $this->routeUpdate(), $sendData);

        if (!in_array($response->status(), [200, 201])) {
            throw new Exception("Response must be 200, given {$response->status()} : \n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatase);
        $this->assertJsonResponseContent($response, $testDatase, $testJsonData);


        return $response;
    }

    private function assertInDatabase(TestResponse $response, array $testDatase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();

        $this->assertDatabaseHas($table, $testDatase + ['id' => $this->getIdFromResponse($response)]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatase, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDatase;
        $response->assertJsonFragment($testResponse + ['id' => $this->getIdFromResponse($response)]);
    }

    private function getIdFromResponse(TestResponse $response)
    {
        return $response->json('id') ?? $response->json('data.id');
    }
}
