"use strict"

function rating() {
    const color = "rgb(253 224 71)"
    const [action, id] = this.href.split('/').slice(-2)
    const method = (action == "remove") ? "DELETE" : "POST"
    const branchId = this.dataset.branch

    console.log(branchId);

    fetch('/api/rating/' + action + '/' + id, {
        method: method,
        body: JSON.stringify(branchId)
    })
        .then((response) => response.json())
        .then((json) => {
            console.log(json)
            // json.controls.forEach(node => {
            //     const fill = (node.fill) ? color : "none"
            //     const el = document.getElementById(node.id)
            //     el.href = node.href
            //     el.children[0].setAttribute("fill", fill)
            // })

            // document.getElementById('avg-' + id).textContent = Math.round(json.avg * 100) / 100
        })
}

function timer() {
    const countdown = document.querySelector('span.countdown')

    if (countdown) {
        const sh = document.querySelector('span#hours')
        const sm = document.querySelector('span#min')
        const ss = document.querySelector('span#sec')

        let tl = parseInt(countdown.dataset.time, 10)

        if (!isNaN(tl)) {
            const timer = setInterval(() => {
                let h = Math.floor(tl / 60 / 60)
                let m = Math.floor((tl - h * 60 * 60) / 60)
                let s = tl % 60

                sh.style.setProperty('--value', h)
                sm.style.setProperty('--value', m)
                ss.style.setProperty('--value', s)

                --tl

                if (tl < 0) {
                    clearInterval(timer);
                    countdown.innerHTML = "EXPIRED";
                    expiredCheck()
                }
            }, 1000)
        }
    }
}

function deletePostHandler() {
    const nodeDelPostList = document.querySelectorAll('a[href*="delete"]')
    const regDel = /post\/\d\/delete\/\d/
    const nodeDelPostArr = Array.from(nodeDelPostList).filter(el => regDel.test(el.href))

    nodeDelPostArr.forEach(item => {
        item.addEventListener("click", function (event) {
            event.preventDefault()
            const confirmDialogWrapper = document.createElement('div')
            confirmDialogWrapper.className = "flex justify-between alert alert-warning rounded-t-none rounded-b-md";

            const msg = document.createElement('span')
            msg.innerHTML = "<strong>Warning!</strong> This post will be deleted"

            const cancelBtn = document.createElement('button')
            cancelBtn.className = "btn btn-neutral md:me-2 mb-1 md:mb-0"
            cancelBtn.onclick = () => { location.reload() }
            cancelBtn.innerText = "Cancel"

            const delBtn = document.createElement('button')
            delBtn.className = "btn btn-primary"
            delBtn.onclick = () => { location.replace(item.href) }
            delBtn.innerText = "Delete"

            const span = document.createElement('span')
            span.className = "text-end"
            span.appendChild(cancelBtn)
            span.appendChild(delBtn)

            confirmDialogWrapper.appendChild(msg)
            confirmDialogWrapper.appendChild(span)

            item.closest('div.flex').replaceWith(confirmDialogWrapper)
        })
    })
}
