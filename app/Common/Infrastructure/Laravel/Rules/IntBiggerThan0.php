<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Rules;

use Closure;
use olml89\IPGlobalTest\Common\Domain\Validation\IntBiggerThan0 as DomainRule;
use Illuminate\Contracts\Validation\ValidationRule;

class IntBiggerThan0 implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_int($value)) {
            $fail('The :attribute must be an integer');
            return;
        }

        $rule = new DomainRule($value);

        if (!$rule->check()) {
            $fail($rule->failureMessage());
        }
    }
}
