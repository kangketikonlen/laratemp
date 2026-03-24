import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.data('wysiwygEditor', ({ value = '', placeholder = '' } = {}) => ({
    value,
    placeholder,
    isEmpty: true,
    init() {
        this.$refs.editor.innerHTML = this.normalize(this.value)
        this.sync()
    },
    format(command, arg = null) {
        this.$refs.editor.focus()
        document.execCommand(command, false, arg)
        this.sync()
    },
    sync() {
        const html = this.normalize(this.$refs.editor.innerHTML)

        if (html !== this.$refs.editor.innerHTML) {
            this.$refs.editor.innerHTML = html
        }

        this.value = html
        this.isEmpty = this.toPlainText(html).length === 0
    },
    normalize(html) {
        const cleaned = String(html ?? '')
            .replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '')
            .replace(/ on\w+="[^"]*"/gi, '')
            .replace(/ on\w+='[^']*'/gi, '')
            .trim()

        return this.toPlainText(cleaned).length === 0 ? '' : cleaned
    },
    toPlainText(html) {
        const container = document.createElement('div')
        container.innerHTML = html

        return (container.textContent || container.innerText || '').replace(/\u00a0/g, ' ').trim()
    },
}))

Alpine.start()
