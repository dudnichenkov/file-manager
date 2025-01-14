<?php

namespace Dietrichxx\FileManager\Rules;

use Closure;
use Dietrichxx\FileManager\Models\Interfaces\ValidationSettingsInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class FileValidation implements ValidationRule
{
    public ValidationSettingsInterface $settings;

    public function __construct(ValidationSettingsInterface $settings)
    {
        $this->settings = $settings;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxSize = $this->settings->getMaxFileSizeMB() * 1024 * 1024;
        if ($value->getSize() > $maxSize) {
            $fail("The {$attribute} must not be greater than {$this->settings->getMaxFileSizeMB()} MB.");
        }

        if (!$this->settings->isAllowAllExtensions()) {
            $allowedExtensions = $this->settings->getAllowedExtensions();
            if ($allowedExtensions && !in_array($value->getClientOriginalExtension(), $allowedExtensions)) {
                $fail("The {$attribute} has an invalid file extension.");
            }
        }

        $disallowedExtensions = $this->settings->getDisallowedExtensions();
        if ($disallowedExtensions && in_array($value->getClientOriginalExtension(), $disallowedExtensions)) {
            $fail("The {$attribute} has a disallowed file extension.");
        }
    }
}
