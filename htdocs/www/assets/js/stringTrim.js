document.addEventListener("DOMContentLoaded", stringTrim);

function stringTrim() {
    document.querySelectorAll('[trim-line]').forEach(
        (node) => {
            limit = Number(node.getAttribute('trim-line'))
            const text = node.textContent.trim()
            node.textContent = text.length < limit
                ? text
                : text.slice(0, limit).trim()
                    + String.fromCharCode(8230)
        }
    )
}
