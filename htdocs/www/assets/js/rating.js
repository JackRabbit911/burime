const color = "rgb(253 224 71)"
const nodeList = document.querySelectorAll('a[href*="rating"]')

nodeList.forEach(item => {
    item.addEventListener("click", function (event) {
        event.preventDefault()
        segments = (item.href.split('/'))
        const [action, id] = segments.slice(-2);

        method = (action == "remove") ? "DELETE" : "POST"
        
        fetch('/api/rating/' + action + '/' + id, {
            method: method
        })
        .then((response) => response.json())
        .then((json) => {
            json.controls.forEach(node => {
                fill = (node.fill) ? color : "none"
                el = document.getElementById(node.id)
                el.href = node.href
                el.children[0].setAttribute("fill", fill)
            })
            document.getElementById('avg-' + id).textContent = Math.round(json.avg * 100) / 100
        })       
    })
})
