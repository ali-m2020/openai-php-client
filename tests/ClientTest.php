<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{

    public function testClientWorks(): void
    {
        $client = OpenAI::client("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");
        $response = $client->completions()->create([
            'prompt' => 'PHP is',
            'model' => 'text-davinci-003',
            'max_tokens' => 20,
            'temperature' => 0
        ]);
        // dump($response);

        // Make assertions
        $this->assertNotEmpty($response->id);
        $this->assertSame('text_completion', $response->object);
        $this->assertNotEmpty($response->created);
        $this->assertSame('text-davinci-003', $response->model);
        $this->assertEmpty($response->errStatusCode);
        $this->assertEmpty($response->errMessage);
        $this->assertEmpty($response->errType);
        $this->assertNotEmpty($response->choices[0]);//the actual response of the OpenAi to our prompt
        
    }
}