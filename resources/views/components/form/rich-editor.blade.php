@props([
    'id',
    'name',
    'value' => '',
    'placeholder' => '',
])

<div
    x-data="wysiwygEditor({ value: @js($value), placeholder: @js($placeholder) })"
    x-init="init()"
    class="rich-editor"
>
    <div class="rich-editor__toolbar">
        <button type="button" class="rich-editor__tool" @click="format('bold')" title="Tebal">
            <strong>B</strong>
        </button>
        <button type="button" class="rich-editor__tool italic" @click="format('italic')" title="Miring">
            I
        </button>
        <button type="button" class="rich-editor__tool underline" @click="format('underline')" title="Garis bawah">
            U
        </button>
        <button type="button" class="rich-editor__tool" @click="format('insertUnorderedList')" title="Daftar poin">
            Poin
        </button>
        <button type="button" class="rich-editor__tool" @click="format('insertOrderedList')" title="Daftar bernomor">
            1.
        </button>
        <button type="button" class="rich-editor__tool" @click="format('formatBlock', 'blockquote')" title="Kutipan">
            Kutip
        </button>
        <button type="button" class="rich-editor__tool" @click="format('removeFormat')" title="Hapus format">
            Bersih
        </button>
    </div>

    <div
        id="{{ $id }}_editor"
        x-ref="editor"
        contenteditable="true"
        class="rich-editor__surface"
        :class="{ 'rich-editor__surface--empty': isEmpty }"
        :data-placeholder="placeholder"
        @input="sync()"
        @blur="sync()"
    ></div>

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        x-model="value"
        class="hidden"
    ></textarea>
</div>
