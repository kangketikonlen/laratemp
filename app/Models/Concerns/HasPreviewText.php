<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasPreviewText
{
    public function previewText(int $limit = 140): ?string
    {
        $source = trim(strip_tags($this->previewSourceText()));

        if ($source === '') {
            return null;
        }

        return Str::limit($source, $limit);
    }

    abstract protected function previewSourceText(): string;
}
