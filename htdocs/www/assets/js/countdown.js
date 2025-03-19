const sh = document.querySelector('span#hours')
const sm = document.querySelector('span#min')
const ss = document.querySelector('span#sec')

countdown = document.querySelector('span.countdown')
let tl = parseInt(countdown.dataset.time, 10)
const id = countdown.dataset.id

console.log({id});

if (!isNaN(tl)) {
    tl *= 60
   
    const timer = setInterval(() => {
        let h = Math.floor(tl / 60 / 60)
        let m = Math.floor(tl / 60)
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

function expiredCheck () {

}
