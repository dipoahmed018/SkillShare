import Splide from "@splidejs/splide";
// const { default: axios } = require("axios");
const course_slider_el = document.getElementById('course-slider')
console.log(course_slider_el)

const course_slider = new Splide(course_slider_el, {
  type: 'loop',
  perPage: 2,
  perMove:1,
  width: '100%',
  pagination: false,
  autoplay: true,
  pauseOnHover: true,
  autoHeight: true,
  breakpoints : {
    700 : {
      perPage: 1,
    }
  }
})
document.addEventListener('DOMContentLoaded', () => {
  course_slider.mount()
  console.log(course_slider)
})