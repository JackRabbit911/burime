"use strict"

document.body.onload = () => postWriteTimeLimit()

function rating() {
    const [action, id] = this.href.split('/').slice(-2)
    const method = (action == "remove") ? "DELETE" : "POST"
    const branchId = parseInt(this.dataset.branch, 10)

    fetch('/api/rating/' + action + '/' + id, {
        method: method,
        body: JSON.stringify(branchId)
    })
        .then((response) => response.json())
        .then((json) => {
            json.forEach(item => {
                let node = document.getElementById(item.id)
                node.innerHTML = item.html
            })
        })
}

function postWriteTimeLimit() {
    const countdown = document.querySelector('#timeLimitCounter')

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

function cancelPostHandler() {
    const postId = this.dataset.post
    const postControlsWrapper = document.getElementById('post-controls-wrapper-' + postId)
    this.closest('div.alert').remove();
    postControlsWrapper.classList.remove('hidden');
}

function confirmPostHandler() {
    const postId = this.dataset.post
    const postControlsWrapper = document.getElementById('post-controls-wrapper-' + postId)
    const postConfirmWrapper = document.getElementById('post-confirm-wrapper-' + postId)

    fetch('/api/post/confirm/' + postId)
        .then((response) => response.text())
        .then((text) => {
            postControlsWrapper.classList.add('hidden');
            postConfirmWrapper.innerHTML = text
            const link = postConfirmWrapper.querySelector('a.btn-primary')
            link.href = this.href
        })
}
