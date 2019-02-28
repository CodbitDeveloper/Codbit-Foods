/* Dore Theme Select & Initializer Script 

Table of Contents

01. Css Loading Util
02. Theme Selector And Initializer
*/

(function(w, d){


  function LetterAvatar (name, size) {

      name  = name || '';
      size  = size || 60;

      var colours = [
              "#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", 
              "#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"
          ],

          nameSplit = String(name).toUpperCase().split(' '),
          initials, charIndex, colourIndex, canvas, context, dataURI;


      if (nameSplit.length == 1) {
          initials = nameSplit[0] ? nameSplit[0].charAt(0):'?';
      } else {
          initials = nameSplit[0].charAt(0) + nameSplit[1].charAt(0);
      }

      if (w.devicePixelRatio) {
          size = (size * w.devicePixelRatio);
      }
          
      charIndex     = (initials == '?' ? 72 : initials.charCodeAt(0)) - 64;
      colourIndex   = charIndex % 20;
      canvas        = d.createElement('canvas');
      canvas.width  = size;
      canvas.height = size;
      context       = canvas.getContext("2d");
       
      context.fillStyle = colours[colourIndex - 1];
      context.fillRect (0, 0, canvas.width, canvas.height);
      context.font = Math.round(canvas.width/2)+"px Arial";
      context.textAlign = "center";
      context.fillStyle = "#FFF";
      context.fillText(initials, size / 2, size / 1.5);

      dataURI = canvas.toDataURL();
      canvas  = null;

      return dataURI;
  }

  LetterAvatar.transform = function() {

      Array.prototype.forEach.call(d.querySelectorAll('img[avatar]'), function(img, name) {
          name = img.getAttribute('avatar');
          img.src = LetterAvatar(name, img.getAttribute('width'));
          img.removeAttribute('avatar');
          img.setAttribute('alt', name);
      });
  };


  // AMD support
  if (typeof define === 'function' && define.amd) {
      
      define(function () { return LetterAvatar; });
  
  // CommonJS and Node.js module support.
  } else if (typeof exports !== 'undefined') {
      
      // Support Node.js specific `module.exports` (which can be a function)
      if (typeof module != 'undefined' && module.exports) {
          exports = module.exports = LetterAvatar;
      }

      // But always support CommonJS module 1.1.1 spec (`exports` cannot be a function)
      exports.LetterAvatar = LetterAvatar;

  } else {
      
      window.LetterAvatar = LetterAvatar;

      d.addEventListener('DOMContentLoaded', function(event) {
          LetterAvatar.transform();
      });
  }

})(window, document);
/* 01. Css Loading Util */
function loadStyle(href, callback) {
  for (var i = 0; i < document.styleSheets.length; i++) {
    if (document.styleSheets[i].href == href) {
      return;
    }
  }
  var head = document.getElementsByTagName("head")[0];
  var link = document.createElement("link");
  link.rel = "stylesheet";
  link.type = "text/css";
  link.href = href;
  if (callback) {
    link.onload = function() {
      callback();
    };
  }
  head.appendChild(link);
}
/* 02. Theme Selector And Initializer */
(function($) {
  if ($().dropzone) {
    Dropzone.autoDiscover = false;
  }
  
  var themeColorsDom =
  '<div class="theme-colors"> <div class="p-4"> <p class="text-muted mb-2">Light Theme</p> <div class="d-flex flex-row justify-content-between mb-4"> <a href="#" data-theme="dore.light.red.min.css" class="theme-color theme-color-red"></a> </div> <p class="text-muted mb-2">Dark Theme</p> <div class="d-flex flex-row justify-content-between"><a href="#" data-theme="dore.dark.red.min.css" class="theme-color theme-color-red"></a> </div> </div> <a href="#" class="theme-button"> <i class="simple-icon-settings"></i> </a> </div>';
  $("body").append(themeColorsDom);
  var theme = "dore.light.red.min.css";

  if (typeof Storage !== "undefined") {
    if (localStorage.getItem("theme")) {
      theme = localStorage.getItem("theme");
    }
  }

  $(".theme-color[data-theme='" + theme + "']").addClass("active");

  loadStyle("css/" + theme, onStyleComplete);
  function onStyleComplete() {
    setTimeout(onStyleCompleteDelayed, 300);
  }

  function onStyleCompleteDelayed() {
    var $dore = $("body").dore();
  }

  $("body").on("click", ".theme-color", function(event) {
    event.preventDefault();
    var dataTheme = $(this).data("theme");
    if (typeof Storage !== "undefined") {
      localStorage.setItem("theme", dataTheme);
      window.location.reload();
    }
  });


  $(".theme-button").on("click", function(event) {
    event.preventDefault();
    $(this)
      .parents(".theme-colors")
      .toggleClass("shown");
  });
  $(document).on("click", function(event) {
    if (
      !(
        $(event.target)
          .parents()
          .hasClass("theme-colors") ||
        $(event.target)
          .parents()
          .hasClass("theme-button") ||
        $(event.target).hasClass("theme-button") ||
        $(event.target).hasClass("theme-colors")
      )
    ) {
      if ($(".theme-colors").hasClass("shown")) {
        $(".theme-colors").removeClass("shown");
      }
    }
  });
})(jQuery);



