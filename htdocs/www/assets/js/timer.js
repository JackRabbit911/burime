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
                expiredCheck ()
            }
        }, 1000)
    }
}

function expiredCheck () {

}
