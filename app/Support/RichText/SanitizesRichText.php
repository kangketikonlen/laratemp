<?php

namespace App\Support\RichText;

use DOMDocument;
use DOMElement;
use DOMNode;

trait SanitizesRichText
{
    protected function sanitizeRichText(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $html = trim((string) $value);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<div>'.$html.'</div>';

        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $this->sanitizeNode($dom->documentElement);

        $clean = trim((string) $dom->saveHTML($dom->documentElement));
        $clean = preg_replace('/^<div>|<\/div>$/', '', $clean) ?? $clean;

        return blank(trim(strip_tags($clean))) ? null : $clean;
    }

    protected function sanitizeNode(?DOMNode $node): void
    {
        if (! $node) {
            return;
        }

        foreach (collect(iterator_to_array($node->childNodes)) as $child) {
            if (! $child instanceof DOMNode) {
                continue;
            }

            if ($child instanceof DOMElement) {
                if (! in_array($child->tagName, ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li', 'blockquote'], true)) {
                    $this->unwrapNode($child);
                    continue;
                }

                while ($child->attributes->length > 0) {
                    $child->removeAttributeNode($child->attributes->item(0));
                }
            }

            $this->sanitizeNode($child);
        }
    }

    protected function unwrapNode(DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }
}
