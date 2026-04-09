// Window scroll sticky class add
function windowScroll() {
  var navbar = document.getElementById("navbar");
  if (navbar) {
    if (document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50) {
      navbar.classList.add("is-sticky");
    } else {
      navbar.classList.remove("is-sticky");
    }
  }
}

// Window scroll listener for sticky navbar & back-to-top button
window.addEventListener('scroll', function () {
  windowScroll();

  if (mybutton) {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
      mybutton.style.display = "block";
    } else {
      mybutton.style.display = "none";
    }
  }
});


// Collapse Menu
var navLinks = document.querySelectorAll('.nav-item');
var menuToggle = document.getElementById('navbarSupportedContent');
var bsCollapse = '';

if (navLinks && menuToggle) {
  window.addEventListener('load', function () {
    window.dispatchEvent(new Event('resize'));
  });
  window.addEventListener('resize', function () {
    var windowSize = document.documentElement.clientWidth;
    bsCollapse = new bootstrap.Collapse(menuToggle, { toggle: false });

    if (windowSize < 980) {
      Array.from(navLinks).forEach(function (link) {
        link.addEventListener('click', function () {
          toggleMenu();
        });
      });
    } else {
      toggleMenu();
    }
  });
}

function toggleMenu() {
  var windowSize = document.documentElement.clientWidth;
  if (windowSize < 980) {
    bsCollapse.toggle();
  } else {
    bsCollapse = '';
  }
}

// Trusted client slider
var swiperElem = document.querySelector(".trusted-client-slider");
if (swiperElem) {
  var swiper = new Swiper(".trusted-client-slider", {
    spaceBetween: 30,
    loop: true,
    slidesPerView: 1,
    autoplay: {
      delay: 1000,
      disableOnInteraction: false
    },
    breakpoints: {
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1024: { slidesPerView: 4 }
    }
  });
}

// Pricing toggle
function check() {
  var checkBox = document.getElementById("plan-switch");
  var month = document.getElementsByClassName("month");
  var annual = document.getElementsByClassName("annual");
  var i = 0;
  Array.from(month).forEach(function (mon) {
    if (checkBox && checkBox.checked) {
      if (annual[i]) annual[i].style.display = "block";
      mon.style.display = "none";
    } else {
      if (annual[i]) annual[i].style.display = "none";
      mon.style.display = "block";
    }
    i++;
  });
}
check();

// Counter
function counter() {
  var counters = document.querySelectorAll('.counter-value');
  if (counters.length) {
    var numberWithCommas = function (x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
    var speed = 250; // The lower the slower

    counters.forEach(function (counter_value) {
      function updateCount() {
        var target = +counter_value.getAttribute('data-target');
        var count = +counter_value.innerText;
        var inc = target / speed;

        if (inc < 1) inc = 1;

        if (count < target) {
          counter_value.innerText = (count + inc).toFixed(0);
          setTimeout(updateCount, 1);
        } else {
          counter_value.innerText = numberWithCommas(target);
        }
      }
      updateCount();
    });
  }
}
counter();

// Back to Top button
var mybutton = document.getElementById("back-to-top");

if (mybutton) {
  mybutton.addEventListener('click', function () {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  });
}
