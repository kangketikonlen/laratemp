<?php

use App\Support\RichText\SanitizesRichText;

it('strips disallowed tags and attributes from rich text content', function () {
    $sanitizer = new class
    {
        use SanitizesRichText;

        public function clean(?string $value): ?string
        {
            return $this->sanitizeRichText($value);
        }
    };

    $clean = $sanitizer->clean('<p class="lead">Hello <script>alert(1)</script><strong style="color:red">world</strong></p>');

    expect($clean)->toBe('<p>Hello alert(1)<strong>world</strong></p>');
});

it('returns null when sanitized rich text has no meaningful content', function () {
    $sanitizer = new class
    {
        use SanitizesRichText;

        public function clean(?string $value): ?string
        {
            return $this->sanitizeRichText($value);
        }
    };

    expect($sanitizer->clean('<script>alert(1)</script>'))->toBe('alert(1)')
        ->and($sanitizer->clean('<div>   </div>'))->toBeNull()
        ->and($sanitizer->clean(null))->toBeNull();
});
