particlesJS("particles-js", {
  particles: {
    number: {
      value: 80,
      density: {
        enable: true,
        value_area: 800
      }
    },
    color: {
      value: "#a020f0"
    },
    shape: {
      type: "circle"
    },
    opacity: {
      value: 0.4
    },
    size: {
      value: 4
    },
    line_linked: {
      enable: true,
      distance: 150,
      color: "#a020f0",
      opacity: 0.2,
      width: 1
    },
    move: {
      enable: true,
      speed: 2
    }
  },
  interactivity: {
    detect_on: "canvas",
    events: {
      onhover: {
        enable: true,
        mode: "grab"
      }
    }
  },
  retina_detect: true
});
