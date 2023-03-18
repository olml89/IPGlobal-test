<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Http\Publish;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use olml89\IPGlobalTest\Post\Application\Publish\PublishData;

class LaravelPublishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null): PublishData
    {
        $validatedData = parent::validated($key, $default);

        return new PublishData(
            title: $validatedData['title'],
            body: $validatedData['body'],
        );
    }
}
