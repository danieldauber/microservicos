<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait TestValidations
{

    protected function assertInvalidationInStore(
        array $data,
        string $rule,
        $ruleParams = []
    ) {
        $response = $this->json('POST', $this->routeStore(), $data);

        // dd($response->content());
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationInUpdate(
        array $data,
        string $rule,
        $ruleParams = []
    ) {
        $response = $this->json('PUT', $this->routeUpdate(), $data);

        dd($response->content());
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    ) {

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {

            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);
        }
    }
}
