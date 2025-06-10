// =========================================================
// Soft UI Design System PRO - v1.1.1
// =========================================================

// Product Page: https://www.creative-tim.com/product/soft-ui-design-system-pro
// Copyright 2023 Creative Tim (https://www.creative-tim.com)

// Coded by www.creative-tim.com

// =========================================================

// The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

function smoothToPricing(e) {
  document.getElementById(e) &&
    document.getElementById(e).scrollIntoView({ behavior: "smooth" });
}
function focused(e) {
  e.parentElement.classList.contains("input-group") &&
    e.parentElement.classList.add("focused");
}
function defocused(e) {
  e.parentElement.classList.contains("input-group") &&
    e.parentElement.classList.remove("focused");
}
function setAttributes(t, l) {
  Object.keys(l).forEach(function (e) {
    t.setAttribute(e, l[e]);
  });
}
0 != document.querySelectorAll(".input-group").length &&
  (allInputs = document.querySelectorAll("input.form-control")).forEach(e =>
    setAttributes(e, {
      onfocus: "focused(this)",
      onfocusout: "defocused(this)",
    })
  );
var allInputs,
  popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  ),
  popoverList = popoverTriggerList.map(function (e) {
    return new bootstrap.Popover(e);
  }),
  tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  ),
  tooltipList = tooltipTriggerList.map(function (e) {
    return new bootstrap.Tooltip(e);
  });
function setAttributes(t, l) {
  Object.keys(l).forEach(function (e) {
    t.setAttribute(e, l[e]);
  });
}
var myLatlng,
  mapOptions,
  map,
  marker,
  popoverList = (popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-toggle="popover"]')
  )).map(function (e) {
    return new bootstrap.Popover(e);
  }),
  tooltipList = (tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-toggle="tooltip"]')
  )).map(function (e) {
    return new bootstrap.Tooltip(e);
  });
function dropDown(e) {
  if (!document.querySelector(".dropdown-hover")) {
    event.stopPropagation(), event.preventDefault();
    for (
      var t = e.parentElement.parentElement.children, l = 0;
      l < t.length;
      l++
    )
      t[l].lastElementChild != e.parentElement.lastElementChild &&
        (t[l].lastElementChild.classList.remove("show"),
        t[l].firstElementChild.classList.remove("show"));
    e.nextElementSibling.classList.contains("show")
      ? (e.nextElementSibling.classList.remove("show"),
        e.classList.remove("show"))
      : (e.nextElementSibling.classList.add("show"), e.classList.add("show"));
  }
}
if (document.querySelector(".blur-shadow-image"))
  for (
    var shadowCards = document.querySelectorAll(".blur-shadow-image"), i = 0;
    i < shadowCards.length;
    i++
  ) {
    var div = document.createElement("DIV"),
      currentSrc =
        (shadowCards[i].parentElement.appendChild(div),
        div.classList.add("colored-shadow"),
        shadowCards[i].children[0].getAttribute("src"));
    (el = shadowCards[i].nextElementSibling).style.backgroundImage =
      "url(" + currentSrc + ")";
  }
if (document.querySelector(".blur-shadow-avatar"))
  for (
    shadowCards = document.querySelectorAll(".blur-shadow-avatar"), i = 0;
    i < shadowCards.length;
    i++
  ) {
    for (
      var div = document.createElement("DIV"),
        avatarClasses =
          (shadowCards[i].parentElement.appendChild(div),
          div.classList.add("colored-shadow", "start-0", "end-0", "mx-auto"),
          ["avatar-xs", "avatar-sm", "avatar-lg", "avatar-xl", "avatar-xxl"]),
        k = 0;
      k < avatarClasses.length;
      k++
    )
      shadowCards[i].firstElementChild.classList.contains(avatarClasses[k]) &&
        div.classList.add(avatarClasses[k]);
    var el,
      currentSrc = shadowCards[i].children[0].getAttribute("src");
    (el = shadowCards[i].nextElementSibling).style.backgroundImage =
      "url(" + currentSrc + ")";
  }
document.querySelector("#google-maps") &&
  ((mapOptions = {
    zoom: 13,
    center: (myLatlng = new google.maps.LatLng(40.748817, -73.985428)),
    scrollwheel: !1,
    styles: [
      {
        featureType: "administrative",
        elementType: "labels.text.fill",
        stylers: [{ color: "#444444" }],
      },
      {
        featureType: "landscape",
        elementType: "all",
        stylers: [{ color: "#f2f2f2" }],
      },
      {
        featureType: "poi",
        elementType: "all",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "road",
        elementType: "all",
        stylers: [{ saturation: -100 }, { lightness: 45 }],
      },
      {
        featureType: "road.highway",
        elementType: "all",
        stylers: [{ visibility: "simplified" }],
      },
      {
        featureType: "road.arterial",
        elementType: "labels.icon",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "transit",
        elementType: "all",
        stylers: [{ visibility: "off" }],
      },
      {
        featureType: "water",
        elementType: "all",
        stylers: [{ color: "#C5CBF5" }, { visibility: "on" }],
      },
    ],
  }),
  (map = new google.maps.Map(
    document.getElementById("google-maps"),
    mapOptions
  )),
  (marker = new google.maps.Marker({
    position: myLatlng,
    title: "Hello World!",
  })).setMap(map));
var total = document.querySelectorAll(".nav-pills");
function getEventTarget(e) {
  return (e = e || window.event).target || e.srcElement;
}
function copyCode(e) {
  var t,
    l = window.getSelection(),
    o = document.createRange(),
    r = e.nextElementSibling;
  o.selectNodeContents(r),
    l.removeAllRanges(),
    l.addRange(o),
    document.execCommand("copy");
  window.getSelection().removeAllRanges(),
    e.parentElement.querySelector(".alert") ||
      ((t = document.createElement("div")).classList.add(
        "alert",
        "alert-success",
        "position-absolute",
        "top-0",
        "border-0",
        "text-white",
        "w-25",
        "end-0",
        "start-0",
        "mt-2",
        "mx-auto",
        "py-2"
      ),
      (t.style.transform = "translate3d(0px, 0px, 0px)"),
      (t.style.opacity = "0"),
      (t.style.transition = ".35s ease"),
      setTimeout(function () {
        (t.style.transform = "translate3d(0px, 20px, 0px)"),
          t.style.setProperty("opacity", "1", "important");
      }, 100),
      (t.innerHTML = "Code successfully copied!"),
      e.parentElement.appendChild(t),
      setTimeout(function () {
        (t.style.transform = "translate3d(0px, 0px, 0px)"),
          t.style.setProperty("opacity", "0", "important");
      }, 2e3),
      setTimeout(function () {
        e.parentElement.querySelector(".alert").remove();
      }, 2500));
}
total.forEach(function (n, e) {
  n.querySelector(".moving-tab") && n.querySelector(".moving-tab").remove();
  var s = document.createElement("div"),
    t = n.querySelector("li .nav-link.active"),
    l = t.cloneNode(),
    o =
      ((l.innerHTML = "-"),
      s.classList.add("moving-tab", "position-absolute", "nav-link"),
      s.appendChild(l),
      n.getElementsByTagName("li").length,
      Array.from(t.closest("ul").children).indexOf(t.closest("li")) + 1);
  let r = 0;
  if (
    ((s.style.padding = "0px"),
    (s.style.width = n.querySelector("li .nav-link.active").offsetWidth + "px"),
    n.classList.contains("flex-column"))
  ) {
    for (var a = 1; a < o; a++)
      r += n.querySelector("li:nth-child(" + a + ")").offsetHeight;
    s.style.transform = "translate3d(0px," + r + "px, 0px)";
  } else {
    for (a = 1; a < o; a++)
      r += n.querySelector("li:nth-child(" + a + ")").offsetWidth;
    s.style.transform = "translate3d(" + r + "px, 0px, 0px)";
  }
  n.appendChild(s),
    (s.style.transition = ".5s ease"),
    (n.onmouseover = function (e) {
      let r = getEventTarget(e).closest("li");
      if (r) {
        let l = Array.from(r.closest("ul").children),
          o = l.indexOf(r) + 1;
        n.querySelector("li:nth-child(" + o + ") .nav-link").onclick =
          function () {
            s = n.querySelector(".moving-tab");
            let e = 0;
            if (n.classList.contains("flex-column")) {
              for (var t = 1; t <= l.indexOf(r); t++)
                e += n.querySelector("li:nth-child(" + t + ")").offsetHeight;
              (s.style.transform = "translate3d(0px," + e + "px, 0px)"),
                (s.style.height = n.querySelector(
                  "li:nth-child(" + t + ")"
                ).offsetHeight);
            } else {
              for (t = 1; t <= l.indexOf(r); t++)
                e += n.querySelector("li:nth-child(" + t + ")").offsetWidth;
              (s.style.transform = "translate3d(" + e + "px, 0px, 0px)"),
                (s.style.width =
                  n.querySelector("li:nth-child(" + o + ")").offsetWidth +
                  "px");
            }
          };
      }
    });
}),
  window.addEventListener("resize", function (e) {
    total.forEach(function (t, e) {
      t.querySelector(".moving-tab").remove();
      var l = document.createElement("div"),
        o = t.querySelector(".nav-link.active").cloneNode(),
        r =
          ((o.innerHTML = "-"),
          l.classList.add("moving-tab", "position-absolute", "nav-link"),
          l.appendChild(o),
          t.appendChild(l),
          (l.style.padding = "0px"),
          (l.style.transition = ".5s ease"),
          t.querySelector(".nav-link.active").parentElement);
      if (r) {
        var n = Array.from(r.closest("ul").children),
          o = n.indexOf(r) + 1;
        let e = 0;
        if (t.classList.contains("flex-column")) {
          for (var s = 1; s <= n.indexOf(r); s++)
            e += t.querySelector("li:nth-child(" + s + ")").offsetHeight;
          (l.style.transform = "translate3d(0px," + e + "px, 0px)"),
            (l.style.width =
              t.querySelector("li:nth-child(" + o + ")").offsetWidth + "px"),
            (l.style.height = t.querySelector(
              "li:nth-child(" + s + ")"
            ).offsetHeight);
        } else {
          for (s = 1; s <= n.indexOf(r); s++)
            e += t.querySelector("li:nth-child(" + s + ")").offsetWidth;
          (l.style.transform = "translate3d(" + e + "px, 0px, 0px)"),
            (l.style.width =
              t.querySelector("li:nth-child(" + o + ")").offsetWidth + "px");
        }
      }
    }),
      window.innerWidth < 991
        ? total.forEach(function (e, t) {
            e.classList.contains("flex-column") ||
              e.classList.add("flex-column", "on-resize");
          })
        : total.forEach(function (e, t) {
            e.classList.contains("on-resize") &&
              e.classList.remove("flex-column", "on-resize");
          });
  });
//# sourceMappingURL=_site_kit_pro/assets/js/kit-pro.js.map
