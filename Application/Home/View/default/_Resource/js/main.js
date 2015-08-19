$(function(){
    $('[data-toggle="popover"]').popover(); //一次性初始化所有弹出框

    //背景粒子效果
    if ($('#particles-js').length <= 0) {
        return false;
    }else{
        particlesJS('particles-js', {
            particles: {
                color: '#46BCF3',
                shape: 'circle', // "circle", "edge" or "triangle"
                opacity: 1,
                size: 2,
                size_random: true,
                nb: 200,
                line_linked: {
                    enable_auto: true,
                    distance: 100,
                    color: '#46BCF3',
                    opacity: .8,
                    width: 1,
                    condensed_mode: {
                    enable: false,
                    rotateX: 600,
                    rotateY: 600
                    }
                },
                anim: {
                    enable: true,
                    speed: 1
                }
            },
            interactivity: {
                enable: true,
                mouse: {
                distance: 250
            },
            detect_on: 'canvas', // "canvas" or "window"
                mode: 'grab',
                line_linked: {
                    opacity: .5
                },
                events: {
                    onclick: {
                    enable: true,
                    mode: 'push', // "push" or "remove" (particles)
                    nb: 4
                    }
                }
            },
            /* Retina Display Support */
            retina_detect: true
        });
    }
});
