document.addEventListener("DOMContentLoaded", stringTrim);

function stringTrim() {
    document.querySelectorAll('[trim-line]').forEach(
        (node) => {
            limit = parseInt(node.getAttribute('trim-line'))

            if (!isNaN(limit)) {
                const text = node.textContent.trim()
                node.textContent = text.length < limit
                    ? text
                    : text.slice(0, limit).trim()
                        + String.fromCharCode(8230)
            }
        }
    )
}
