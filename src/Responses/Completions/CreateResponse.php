<?php

declare(strict_types=1);

namespace OpenAI\Responses\Completions;

use OpenAI\Contracts\Response;
use OpenAI\Responses\Concerns\ArrayAccessible;

/**
 * @implements Response<array{id: string, object: string, created: int, model: string, choices: array<int, array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string}>, usage: array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}}>
 */
final class CreateResponse implements Response
{
    /**
     * @use ArrayAccessible<array{id: string, object: string, created: int, model: string, choices: array<int, array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string}>, usage: array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, CreateResponseChoice>  $choices
     */
    private function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly int|string $created,
        public readonly string $model,
        public readonly ?int $errStatusCode,
        public readonly string $errMessage,
        public readonly string $errType,
        public readonly array $choices,
        public readonly CreateResponseUsage $usage,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{id: string, object: string, created: int, model: string, choices: array<int, array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string}>, usage: array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}}  $attributes
     */
    public static function from(array $attributes): self
    {
        $attributes['choices'] = isset($attributes['choices']) && $attributes['choices'] ? $attributes['choices'] : [];
        $attributes['id'] = isset($attributes['id']) && $attributes['id'] ? $attributes['id'] : '';
        $attributes['object'] = isset($attributes['object']) && $attributes['object'] ? $attributes['object'] : '';
        $attributes['created'] = isset($attributes['created']) && $attributes['created'] ? $attributes['created'] : '';
        $attributes['model'] = isset($attributes['model']) && $attributes['model'] ? $attributes['model'] : '';
        $attributes['usage'] = isset($attributes['usage']) && $attributes['usage'] ? $attributes['usage'] : [];
        $attributes['statusCode'] = isset($attributes['statusCode']) && $attributes['statusCode'] ? $attributes['statusCode'] : null;
        $attributes['error']['errMessage'] = isset($attributes['error']['errMessage']) && $attributes['error']['errMessage'] ? $attributes['error']['errMessage'] : '';
        $attributes['error']['errType'] = isset($attributes['error']['errType']) && $attributes['error']['errType'] ? $attributes['error']['errType'] : '';

        $choices = array_map(fn (array $result): CreateResponseChoice => CreateResponseChoice::from(
            $result
        ), $attributes['choices']);

        return new self(
            $attributes['id'],
            $attributes['object'],
            $attributes['created'],
            $attributes['model'],
            $attributes['statusCode'],
            $attributes['errMessage'],
            $attributes['errType'],
            $choices,
            CreateResponseUsage::from($attributes['usage'])
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'created' => $this->created,
            'model' => $this->model,
            'choices' => array_map(
                static fn (CreateResponseChoice $result): array => $result->toArray(),
                $this->choices,
            ),
            'usage' => $this->usage->toArray(),
        ];
    }
}
