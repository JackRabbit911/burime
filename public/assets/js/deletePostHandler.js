const nodeDelPostList = document.querySelectorAll('a[href*="delete"]')
const regDel = /post\/\d\/delete\/\d/
const nodeDelPostArr = Array.from(nodeDelPostList).filter(el => regDel.test(el.href))

nodeDelPostArr.forEach(item => {
    item.addEventListener("click", function (event) {
        event.preventDefault()
        const conf = document.createElement('div')
        conf.className = "flex justify-between alert alert-warning rounded-t-none rounded-b-md";

        const msg = document.createElement('span')
        msg.innerHTML = "<strong>Warning!</strong> This post will be deleted"

        const cancelBtn = document.createElement('button')
        cancelBtn.className = "btn btn-neutral me-2"
        cancelBtn.onclick = () => { location.reload() }
        cancelBtn.innerText = "Cancel"

        const delBtn = document.createElement('button')
        delBtn.className = "btn btn-primary"
        delBtn.onclick = () => { location.replace(item.href) }
        delBtn.innerText = "Delete"

        const span = document.createElement('span')
        span.appendChild(cancelBtn)
        span.appendChild(delBtn)

        conf.appendChild(msg)
        conf.appendChild(span)

        item.closest('div.flex').replaceWith(conf)
    })
})
