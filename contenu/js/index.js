const titreSpans = document.querySelectorAll('h1 span');
const btns = document.querySelectorAll('.btn-first');
const logo = document.querySelectorAll('.logo');
const switch1 = document.querySelector('.switch-mode');
const medias = document.querySelectorAll('.bulle');
const l1 = document.querySelector('.l1');
const l2 = document.querySelector('.l2');

window.addEventListener('load', () => {
    const TL = gsap.timeline({paused: true});

    TL
    .staggerFrom(titreSpans, 1, {top: -50, opacity: 0, ease: "power2.out"}, 0.6)
    .staggerFrom(btns, 1, {opacity:0, ease: "power2.out"}, 0.3, '-=1')
    .from(l1, 1, {width: 0, ease: "power2.out"}, '-=2')
    .from(l2, 1, {width: 0, ease: "power2.out"}, '-=2')
    .from(logo, 0.4, {transform: "scale(0)", ease: "power2.out"}, '-=2')
    .from(switch1, 0.4, {transform: "scale(0)", ease: "power2.out"}, '-=2')
    .staggerFrom(medias, 0.8, {right: -200, ease: "power2.out"}, 0.3, '-=1')

    TL.play();
})

/* Les modes de constrastes */
function dark() {
    var a = document.getElementById("click");

    if(a.checked == true){
        document.documentElement.style.setProperty("--background", "rgba(60, 60, 60, 0.6)");
        document.documentElement.style.setProperty("--background-btn", "rgba(60, 60, 60, 0.9)");
        document.documentElement.style.setProperty("--trait", "#222");

    } else {
        document.documentElement.style.setProperty("--background", "transparent");
        document.documentElement.style.setProperty("--background-btn", "rgba(60, 60, 100, 0.5)");
        document.documentElement.style.setProperty("--trait", "#F1F1F1");

    }
}