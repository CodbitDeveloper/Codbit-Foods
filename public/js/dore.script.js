$.fn.addCommas = function(nStr) {
  nStr += "";
  var x = nStr.split(".");
  var x1 = x[0];
  var x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
};

/* 02. Shift Select Plugin */
$.shiftSelectable = function(element, config) {
  var plugin = this;
  config = $.extend(
    {
      items: ".card"
    },
    config
  );
  var $container = $(element);
  var $checkAll = null;
  var $boxes = $container.find("input[type='checkbox']");

  var lastChecked;
  if ($container.data("checkAll")) {
    $checkAll = $("#" + $container.data("checkAll"));
    $checkAll.on("click", function() {
      $boxes.prop("checked", $($checkAll).prop("checked")).trigger("change");
      document.activeElement.blur();
      controlActiveClasses();
    });
  }

  function itemClick(checkbox, shiftKey) {
    $(checkbox)
      .prop("checked", !$(checkbox).prop("checked"))
      .trigger("change");

    if (!lastChecked) {
      lastChecked = checkbox;
    }
    if (lastChecked) {
      if (shiftKey) {
        var start = $boxes.index(checkbox);
        var end = $boxes.index(lastChecked);
        $boxes
          .slice(Math.min(start, end), Math.max(start, end) + 1)
          .prop("checked", lastChecked.checked)
          .trigger("change");
      }
      lastChecked = checkbox;
    }

    if ($checkAll) {
      var anyChecked = false;
      var allChecked = true;
      $boxes.each(function() {
        if ($(this).prop("checked")) {
          anyChecked = true;
        } else {
          allChecked = false;
        }
      });
      if (anyChecked) {
        $checkAll.prop("indeterminate", anyChecked);
      } else {
        $checkAll.prop("indeterminate", anyChecked);
        $checkAll.prop("checked", anyChecked);
      }
      if (allChecked) {
        $checkAll.prop("indeterminate", false);
        $checkAll.prop("checked", allChecked);
      }
    }
    document.activeElement.blur();
    controlActiveClasses();
  }

  $container.on("click", config.items, function(e) {
    if (
      $(e.target).is("a") ||
      $(e.target)
        .parent()
        .is("a")
    ) {
      return;
    }

    if ($(e.target).is("input[type='checkbox']")) {
      e.preventDefault();
      e.stopPropagation();
    }
    var checkbox = $(this).find("input[type='checkbox']")[0];
    itemClick(checkbox, e.shiftKey);
  });

  function controlActiveClasses() {
    $boxes.each(function() {
      if ($(this).prop("checked")) {
        $(this)
          .parents(".card")
          .addClass("active");
      } else {
        $(this)
          .parents(".card")
          .removeClass("active");
      }
    });
  }

  plugin.selectAll = function() {
    if ($checkAll) {
      $boxes.prop("checked", true).trigger("change");
      $checkAll.prop("checked", true);
      $checkAll.prop("indeterminate", false);
      controlActiveClasses();
    }
  };

  plugin.deSelectAll = function() {
    if ($checkAll) {
      $boxes.prop("checked", false).trigger("change");
      $checkAll.prop("checked", false);
      $checkAll.prop("indeterminate", false);
      controlActiveClasses();
    }
  };

  plugin.rightClick = function(trigger) {
    var checkbox = $(trigger).find("input[type='checkbox']")[0];
    if ($(checkbox).prop("checked")) {
      return;
    }
    plugin.deSelectAll();
    itemClick(checkbox, false);
  };
};

$.fn.shiftSelectable = function(options) {
  return this.each(function() {
    if (undefined == $(this).data("shiftSelectable")) {
      var plugin = new $.shiftSelectable(this, options);
      $(this).data("shiftSelectable", plugin);
    }
  });
};

/* 03. Dore Main Plugin */
$.dore = function(element, options) {
  var defaults = {};
  var plugin = this;
  plugin.settings = {};
  var $element = $(element);
  var element = element;

  var $shiftSelect;

  function init() {
    options = options || {};
    plugin.settings = $.extend({}, defaults, options);
    /* 03.01. Getting Colors from CSS */
    var rootStyle = getComputedStyle(document.body);
    var themeColor1 = rootStyle.getPropertyValue("--theme-color-1").trim();
    var themeColor2 = rootStyle.getPropertyValue("--theme-color-2").trim();
    var themeColor3 = rootStyle.getPropertyValue("--theme-color-3").trim();
    var themeColor4 = rootStyle.getPropertyValue("--theme-color-4").trim();
    var themeColor5 = rootStyle.getPropertyValue("--theme-color-5").trim();
    var themeColor6 = rootStyle.getPropertyValue("--theme-color-6").trim();
    var themeColor1_10 = rootStyle
      .getPropertyValue("--theme-color-1-10")
      .trim();
    var themeColor2_10 = rootStyle
      .getPropertyValue("--theme-color-2-10")
      .trim();
    var themeColor3_10 = rootStyle
      .getPropertyValue("--theme-color-3-10")
      .trim();
    var themeColor4_10 = rootStyle
      .getPropertyValue("--theme-color-4-10")
      .trim();

    var themeColor5_10 = rootStyle
      .getPropertyValue("--theme-color-5-10")
      .trim();
    var themeColor6_10 = rootStyle
      .getPropertyValue("--theme-color-6-10")
      .trim();

    var primaryColor = rootStyle.getPropertyValue("--primary-color").trim();
    var foregroundColor = rootStyle
      .getPropertyValue("--foreground-color")
      .trim();
    var separatorColor = rootStyle.getPropertyValue("--separator-color").trim();

    /* 03.02. Resize */
    var subHiddenBreakpoint = 1440;
    var searchHiddenBreakpoint = 768;
    var menuHiddenBreakpoint = 768;
    var subHiddenByClick = false;
    var firstInit = true;

    function onResize() {
      var windowHeight = $(window).outerHeight();
      var windowWidth = $(window).outerWidth();
      var navbarHeight = $(".navbar").outerHeight();

      var submenuMargin = parseInt(
        $(".sub-menu .scroll").css("margin-top"),
        10
      );
      $(".sub-menu .scroll").css(
        "height",
        windowHeight - navbarHeight - submenuMargin * 2
      );

      $(".main-menu .scroll").css("height", windowHeight - navbarHeight);
      $(".app-menu .scroll").css("height", windowHeight - navbarHeight - 40);

      if ($(".chat-app .scroll").length > 0 && chatAppScroll) {
        $(".chat-app .scroll").scrollTop(
          $(".chat-app .scroll").prop("scrollHeight")
        );
        chatAppScroll.update();
      }

      if (windowWidth < menuHiddenBreakpoint) {
        $("#app-container").addClass("menu-mobile");
      } else if (windowWidth < subHiddenBreakpoint) {
        $("#app-container").removeClass("menu-mobile");
        if ($("#app-container").hasClass("menu-default")) {
          // $("#app-container").attr("class", "menu-default menu-sub-hidden");
          $("#app-container").removeClass(allMenuClassNames);
          $("#app-container").addClass("menu-default menu-sub-hidden");
        }
      } else {
        $("#app-container").removeClass("menu-mobile");
        if (
          $("#app-container").hasClass("menu-default") &&
          $("#app-container").hasClass("menu-sub-hidden")
        ) {
          $("#app-container").removeClass("menu-sub-hidden");
        }
      }
      
      setMenuClassNames(0, true);
    }

    $(window).on("resize", function(event) {
      if (event.originalEvent.isTrusted) {
        onResize();
      }
    });
    onResize();

    /* 03.03. Search */
    function searchIconClick() {
      if ($(window).outerWidth() < searchHiddenBreakpoint) {
        if ($(".search").hasClass("mobile-view")) {
          $(".search").removeClass("mobile-view");
          navigateToSearchPage();
        } else {
          $(".search").addClass("mobile-view");
          $(".search input").focus();
        }
      } else {
        navigateToSearchPage();
      }
    }

    $(".search .search-icon").on("click", function() {
      searchIconClick();
    });

    $(".search input").on("keyup", function(e) {
      if (e.which == 13) {
        navigateToSearchPage();
      }
      if (e.which == 27) {
        hideSearchArea();
      }
    });

    function navigateToSearchPage() {
      var inputVal = $(".search input").val();
      var searchPath = $(".search").data("searchPath");
      if (inputVal != "") {
        $(".search input").val("");
        window.location.href = searchPath + inputVal;
      }
    }

    function hideSearchArea() {
      if ($(".search").hasClass("mobile-view")) {
        $(".search").removeClass("mobile-view");
        $(".search input").val("");
      }
    }

    $(document).on("click", function(event) {
      if (
        !$(event.target)
          .parents()
          .hasClass("search")
      ) {
        hideSearchArea();
      }
    });

    /* 03.04. Shift Selectable Init */
    $shiftSelect = $(".list").shiftSelectable();

    /* 03.05. Menu */
    var menuClickCount = 0;
    var allMenuClassNames = "menu-default menu-hidden sub-hidden main-hidden menu-sub-hidden main-show-temporary sub-show-temporary menu-mobile";
    function setMenuClassNames(clickIndex, calledFromResize, link) {
      menuClickCount = clickIndex;
      var container = $("#app-container");
      if (container.length == 0) {
        return;
      }

      var link = link || getActiveMainMenuLink();

      //menu-default no subpage
      if (
        $(".sub-menu ul[data-link='" + link + "']").length == 0 &&
        (menuClickCount == 2 || calledFromResize)
      ) {
        if ($(window).outerWidth() >= menuHiddenBreakpoint) {
          if (isClassIncludedApp("menu-default")) {
            if (calledFromResize) {
              // $("#app-container").attr(
              //   "class",
              //   "menu-default menu-sub-hidden sub-hidden"
              // );
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-default menu-sub-hidden sub-hidden");
              menuClickCount = 1;
            } else {
              // $("#app-container").attr(
              //   "class",
              //   "menu-default main-hidden menu-sub-hidden sub-hidden"
              // );
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-default main-hidden menu-sub-hidden sub-hidden");

              menuClickCount = 0;
            }
            resizeCarousel();
            return;
          }
        }
      }

      //menu-sub-hidden no subpage
      if (
        $(".sub-menu ul[data-link='" + link + "']").length == 0 &&
        (menuClickCount == 1 || calledFromResize)
      ) {
        if ($(window).outerWidth() >= menuHiddenBreakpoint) {
          if (isClassIncludedApp("menu-sub-hidden")) {
            if (calledFromResize) {
              // $("#app-container").attr("class", "menu-sub-hidden sub-hidden");
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-sub-hidden sub-hidden");
              menuClickCount = 0;
            } else {
              // $("#app-container").attr(
              //   "class",
              //   "menu-sub-hidden main-hidden sub-hidden"
              // );
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-sub-hidden main-hidden sub-hidden");
              menuClickCount = -1;
            }
            resizeCarousel();
            return;
          }
        }
      }

      //menu-sub-hidden no subpage
      if (
        $(".sub-menu ul[data-link='" + link + "']").length == 0 &&
        (menuClickCount == 1 || calledFromResize)
      ) {
        if ($(window).outerWidth() >= menuHiddenBreakpoint) {
          if (isClassIncludedApp("menu-hidden")) {
            if (calledFromResize) {
              // $("#app-container").attr(
              //   "class",
              //   "menu-hidden main-hidden sub-hidden"
              // );
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-hidden main-hidden sub-hidden");

              menuClickCount = 0;
            } else {
              // $("#app-container").attr(
              //   "class",
              //   "menu-hidden main-show-temporary"
              // );
              $("#app-container").removeClass(allMenuClassNames);
              $("#app-container").addClass("menu-hidden main-show-temporary");

              menuClickCount = 3;
            }
            resizeCarousel();
            return;
          }
        }
      }

      if (clickIndex % 4 == 0) {
        if (
          isClassIncludedApp("menu-default") &&
          isClassIncludedApp("menu-sub-hidden")
        ) {
          nextClasses = "menu-default menu-sub-hidden";
        } else if (isClassIncludedApp("menu-default")) {
          nextClasses = "menu-default";
        } else if (isClassIncludedApp("menu-sub-hidden")) {
          nextClasses = "menu-sub-hidden";
        } else if (isClassIncludedApp("menu-hidden")) {
          nextClasses = "menu-hidden";
        }
        menuClickCount = 0;
      } else if (clickIndex % 4 == 1) {
        if (
          isClassIncludedApp("menu-default") &&
          isClassIncludedApp("menu-sub-hidden")
        ) {
          nextClasses = "menu-default menu-sub-hidden main-hidden sub-hidden";
        } else if (isClassIncludedApp("menu-default")) {
          nextClasses = "menu-default sub-hidden";
        } else if (isClassIncludedApp("menu-sub-hidden")) {
          nextClasses = "menu-sub-hidden main-hidden sub-hidden";
        } else if (isClassIncludedApp("menu-hidden")) {
          nextClasses = "menu-hidden main-show-temporary";
        }
      } else if (clickIndex % 4 == 2) {
        if (
          isClassIncludedApp("menu-default") &&
          isClassIncludedApp("menu-sub-hidden")
        ) {
          nextClasses = "menu-default menu-sub-hidden sub-hidden";
        } else if (isClassIncludedApp("menu-default")) {
          nextClasses = "menu-default main-hidden sub-hidden";
        } else if (isClassIncludedApp("menu-sub-hidden")) {
          nextClasses = "menu-sub-hidden sub-hidden";
        } else if (isClassIncludedApp("menu-hidden")) {
          nextClasses = "menu-hidden main-show-temporary sub-show-temporary";
        }
      } else if (clickIndex % 4 == 3) {
        if (
          isClassIncludedApp("menu-default") &&
          isClassIncludedApp("menu-sub-hidden")
        ) {
          nextClasses = "menu-default menu-sub-hidden sub-show-temporary";
        } else if (isClassIncludedApp("menu-default")) {
          nextClasses = "menu-default sub-hidden";
        } else if (isClassIncludedApp("menu-sub-hidden")) {
          nextClasses = "menu-sub-hidden sub-show-temporary";
        } else if (isClassIncludedApp("menu-hidden")) {
          nextClasses = "menu-hidden main-show-temporary";
        }
      }
      if (isClassIncludedApp("menu-mobile")) {
        nextClasses += " menu-mobile";
      }
      // container.attr("class", nextClasses);
      container.removeClass(allMenuClassNames);
      container.addClass(nextClasses);
      resizeCarousel();
    }
    $(".menu-button").on("click", function(event) {
      event.preventDefault();
      setMenuClassNames(++menuClickCount);
    });

    $(".menu-button-mobile").on("click", function(event) {
      event.preventDefault();
      $("#app-container")
        .removeClass("sub-show-temporary")
        .toggleClass("main-show-temporary");
      return false;
    });

    $(".main-menu").on("click", "a", function(event) {
      event.preventDefault();

      var link = $(this)
        .attr("href")
        .replace("#", "");

      if ($(".sub-menu ul[data-link='" + link + "']").length == 0) {
        window.location.href = link;
        return;
      }

      showSubMenu($(this).attr("href"));
      var container = $("#app-container");
      if (!$("#app-container").hasClass("menu-mobile")) {
        if (
          $("#app-container").hasClass("menu-sub-hidden") &&
          (menuClickCount == 2 || menuClickCount == 0)
        ) {
          setMenuClassNames(3, false, link);
        } else if (
          $("#app-container").hasClass("menu-hidden") &&
          (menuClickCount == 1 || menuClickCount == 3)
        ) {
          setMenuClassNames(2, false, link);
        } else if (
          $("#app-container").hasClass("menu-default") &&
          !$("#app-container").hasClass("menu-sub-hidden") &&
          (menuClickCount == 1 || menuClickCount == 3)
        ) {
          setMenuClassNames(0, false, link);
        }
      } else {
        $("#app-container").addClass("sub-show-temporary");
      }
      return false;
    });

    $(document).on("click", function(event) {
      if (
        !(
          $(event.target)
            .parents()
            .hasClass("menu-button") ||
          $(event.target).hasClass("menu-button") ||
          $(event.target)
            .parents()
            .hasClass("sidebar") ||
          $(event.target).hasClass("sidebar")
        )
      ) {
        if (
          $("#app-container").hasClass("menu-sub-hidden") &&
          menuClickCount == 3
        ) {
          var link = getActiveMainMenuLink();

          if (link == lastActiveSubmenu) {
            setMenuClassNames(2);
          } else {
            setMenuClassNames(0);
          }
        } else if (
          $("#app-container").hasClass("menu-hidden") ||
          $("#app-container").hasClass("menu-mobile")
        ) {
          setMenuClassNames(0);
        }
      }
    });

    function getActiveMainMenuLink() {
      var dataLink = $(".main-menu ul li.active a").attr("href");
      if(dataLink == null){
        return "";
      }else{
        return dataLink.replace("#", "");
      }
    }

    function isClassIncludedApp(className) {
      var container = $("#app-container");
      var currentClasses = container
        .attr("class")
        .split(" ")
        .filter(x => x != "");
      return currentClasses.includes(className);
    }

    var lastActiveSubmenu = "";
    function showSubMenu(dataLink) {
      if ($(".main-menu").length == 0) {
        return;
      }

      var link = dataLink.replace("#", "");
      if ($(".sub-menu ul[data-link='" + link + "']").length == 0) {
        $("#app-container").removeClass("sub-show-temporary");

        if ($("#app-container").length == 0) {
          return;
        }

        if (
          isClassIncludedApp("menu-sub-hidden") ||
          isClassIncludedApp("menu-hidden")
        ) {
          menuClickCount = 0;
        } else {
          menuClickCount = 1;
        }
        $("#app-container").addClass("sub-hidden");
        noTransition();
        return;
      }
      if (link == lastActiveSubmenu) {
        return;
      }
      $(".sub-menu ul").fadeOut(0);
      $(".sub-menu ul[data-link='" + link + "']").slideDown(100);

      $(".sub-menu .scroll").scrollTop(0);
      lastActiveSubmenu = link;
    }

    function noTransition() {
      $(".sub-menu").addClass("no-transition");
      $("main").addClass("no-transition");
      setTimeout(function() {
        $(".sub-menu").removeClass("no-transition");
        $("main").removeClass("no-transition");
      }, 350);
    }

    showSubMenu($(".main-menu ul li.active a").attr("href"));

    function resizeCarousel() {
      setTimeout(function() {
        var event = document.createEvent("HTMLEvents");
        event.initEvent("resize", false, false);
        window.dispatchEvent(event);
      }, 350);
    }

    /* 03.06. App Menu */
    $(".app-menu-button").on("click", function() {
      event.preventDefault();
      if ($(".app-menu").hasClass("shown")) {
        $(".app-menu").removeClass("shown");
      } else {
        $(".app-menu").addClass("shown");
      }
    });

    $(document).on("click", function(event) {
      if (
        !(
          $(event.target)
            .parents()
            .hasClass("app-menu") ||
          $(event.target)
            .parents()
            .hasClass("app-menu-button") ||
          $(event.target).hasClass("app-menu-button") ||
          $(event.target).hasClass("app-menu")
        )
      ) {
        if ($(".app-menu").hasClass("shown")) {
          $(".app-menu").removeClass("shown");
        }
      }
    });

    /* 03.08. Rotate Button */
    $(document).on("click", ".rotate-icon-click", function() {
      $(this).toggleClass("rotate");
    });

    /* 03.10. Calendar */
    if ($().fullCalendar) {
      var testEvent = new Date(new Date().setHours(new Date().getHours()));
      var day = testEvent.getDate();
      var month = testEvent.getMonth() + 1;
      $(".calendar").fullCalendar({
        themeSystem: "bootstrap4",
        height: "auto",
        buttonText: {
          today: "Today",
          month: "Month",
          week: "Week",
          day: "Day",
          list: "List"
        },
        bootstrapFontAwesome: {
          prev: " simple-icon-arrow-left",
          next: " simple-icon-arrow-right",
          prevYear: "simple-icon-control-start",
          nextYear: "simple-icon-control-end"
        },
        events: [
          {
            title: "Account",
            start: "2018-05-18"
          },
          {
            title: "Delivery",
            start: "2018-09-22",
            end: "2018-09-24"
          },
          {
            title: "Conference",
            start: "2018-06-07",
            end: "2018-06-09"
          },
          {
            title: "Delivery",
            start: "2018-11-03",
            end: "2018-11-06"
          },
          {
            title: "Meeting",
            start: "2018-10-07",
            end: "2018-10-09"
          },
          {
            title: "Taxes",
            start: "2018-08-07",
            end: "2018-08-09"
          }
        ]
      });
    }

    /* 03.11. Datatable */
    if ($().DataTable) {
      $(".data-table").DataTable({
        searching: false,
        bLengthChange: false,
        destroy: true,
        info: false,
        sDom:
          '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
        pageLength: 6,
        language: {
          paginate: {
            previous: "<i class='simple-icon-arrow-left'></i>",
            next: "<i class='simple-icon-arrow-right'></i>"
          }
        },

        drawCallback: function() {
          $($(".dataTables_wrapper .pagination li:first-of-type"))
            .find("a")
            .addClass("prev");
          $($(".dataTables_wrapper .pagination li:last-of-type"))
            .find("a")
            .addClass("next");

          $(".dataTables_wrapper .pagination").addClass("pagination-sm");
        }
      });
    }


    /* 03.13. Owl carousel */
    function initCarousel(){
      if ($().owlCarousel) {
        if ($(".owl-carousel.basic").length > 0) {
          $(".owl-carousel.basic")
            .owlCarousel({
              margin: 30,
              stagePadding: 15,
              dotsContainer: $(".owl-carousel.basic")
                .parents(".owl-container")
                .find(".slider-dot-container"),
              responsive: {
                0: {
                  items: 1
                },
                600: {
                  items: 2
                },
                1000: {
                  items: 3
                }
              }
            })
            .data("owl.carousel")
            .onResize();
        }

        if ($(".best-rated-items").length > 0) {
          $(".best-rated-items")
            .owlCarousel({
              margin: 15,
              items: 1,
              loop: true,
              autoWidth: true
            })
            .data("owl.carousel")
            .onResize();
        }

        if ($(".owl-carousel.single").length > 0) {
          $(".owl-carousel.single")
            .owlCarousel({
              margin: 30,
              items: 1,
              loop: true,
              stagePadding: 15,
              dotsContainer: $(".owl-carousel.single")
                .parents(".owl-container")
                .find(".slider-dot-container")
            })
            .data("owl.carousel")
            .onResize();
        }

        if ($(".owl-carousel.center").length > 0) {
          $(".owl-carousel.center")
            .owlCarousel({
              loop: true,
              margin: 30,
              stagePadding: 15,
              center: true,
              dotsContainer: $(".owl-carousel.center")
                .parents(".owl-container")
                .find(".slider-dot-container"),
              responsive: {
                0: {
                  items: 1
                },
                480: {
                  items: 2
                },
                600: {
                  items: 3
                },
                1000: {
                  items: 4
                }
              }
            })
            .data("owl.carousel")
            .onResize();
        }

        $(".owl-dot").click(function() {
          var carouselReference = $(
            $(this)
              .parents(".owl-container")
              .find(".owl-carousel")
          ).owlCarousel();
          carouselReference.trigger("to.owl.carousel", [$(this).index(), 300]);
        });

        $(".owl-prev").click(function(event) {
          event.preventDefault();
          var carouselReference = $(
            $(this)
              .parents(".owl-container")
              .find(".owl-carousel")
          ).owlCarousel();
          carouselReference.trigger("prev.owl.carousel", [300]);
        });

        $(".owl-next").click(function(event) {
          event.preventDefault();
          var carouselReference = $(
            $(this)
              .parents(".owl-container")
              .find(".owl-carousel")
          ).owlCarousel();
          carouselReference.trigger("next.owl.carousel", [300]);
        });
      }
  }

    /* 03.14. Slick Slider */
    if ($().slick) {
      $(".slick.basic").slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 4,
        appendDots: $(".slick.basic")
          .parents(".slick-container")
          .find(".slider-dot-container"),
        prevArrow: $(".slick.basic")
          .parents(".slick-container")
          .find(".slider-nav .left-arrow"),
        nextArrow: $(".slick.basic")
          .parents(".slick-container")
          .find(".slider-nav .right-arrow"),
        customPaging: function(slider, i) {
          return '<button role="button" class="slick-dot"><span></span></button>';
        },
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });

      $(".slick.center").slick({
        dots: true,
        infinite: true,
        centerMode: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        appendDots: $(".slick.center")
          .parents(".slick-container")
          .find(".slider-dot-container"),
        prevArrow: $(".slick.center")
          .parents(".slick-container")
          .find(".slider-nav .left-arrow"),
        nextArrow: $(".slick.center")
          .parents(".slick-container")
          .find(".slider-nav .right-arrow"),
        customPaging: function(slider, i) {
          return '<button role="button" class="slick-dot"><span></span></button>';
        },
        responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true,
              centerMode: false
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              centerMode: false
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              centerMode: false
            }
          }
        ]
      });

      $(".slick.single").slick({
        dots: true,
        infinite: true,
        speed: 300,
        appendDots: $(".slick.single")
          .parents(".slick-container")
          .find(".slider-dot-container"),
        prevArrow: $(".slick.single")
          .parents(".slick-container")
          .find(".slider-nav .left-arrow"),
        nextArrow: $(".slick.single")
          .parents(".slick-container")
          .find(".slider-nav .right-arrow"),
        customPaging: function(slider, i) {
          return '<button role="button" class="slick-dot"><span></span></button>';
        }
      });
    }

    /* 03.15. Form Validation */
    var forms = document.getElementsByClassName("needs-validation");
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener(
        "submit",
        function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add("was-validated");
        },
        false
      );
    });

    /* 03.16. Tooltip */
    if ($().tooltip) {
      $('[data-toggle="tooltip"]').tooltip();
    }

    /* 03.17. Popover */
    if ($().popover) {
      $('[data-toggle="popover"]').popover({ trigger: "focus" });
    }

    /* 03.18. Select 2 */
    if ($().select2) {
      $(".select2-single, .select2-multiple").select2({
        theme: "bootstrap",
        placeholder: "",
        maximumSelectionSize: 6,
        containerCssClass: ":all:"
      });
    }

    /* 03.19. Datepicker */
    if ($().datepicker) {
      $("input.datepicker").datepicker({
        autoclose: true,
        templates: {
          leftArrow: '<i class="simple-icon-arrow-left"></i>',
          rightArrow: '<i class="simple-icon-arrow-right"></i>'
        }
      });

      $(".input-daterange").datepicker({
        autoclose: true,
        templates: {
          leftArrow: '<i class="simple-icon-arrow-left"></i>',
          rightArrow: '<i class="simple-icon-arrow-right"></i>'
        }
      });

      $(".input-group.date").datepicker({
        autoclose: true,
        templates: {
          leftArrow: '<i class="simple-icon-arrow-left"></i>',
          rightArrow: '<i class="simple-icon-arrow-right"></i>'
        }
      });

      $(".date-inline").datepicker({
        autoclose: true,
        templates: {
          leftArrow: '<i class="simple-icon-arrow-left"></i>',
          rightArrow: '<i class="simple-icon-arrow-right"></i>'
        }
      });
    }

    /* 03.22. Range Slider */
    if (typeof noUiSlider !== "undefined") {
      if ($("#dashboardPriceRange").length > 0) {
        noUiSlider.create($("#dashboardPriceRange")[0], {
          start: [800, 2100],
          connect: true,
          tooltips: true,
          range: {
            min: 200,
            max: 2800
          },
          step: 10,
          format: {
            to: function(value) {
              return "$" + $.fn.addCommas(Math.floor(value));
            },
            from: function(value) {
              return value;
            }
          }
        });
      }

      if ($("#doubleSlider").length > 0) {
        noUiSlider.create($("#doubleSlider")[0], {
          start: [800, 1200],
          connect: true,
          tooltips: true,
          range: {
            min: 500,
            max: 1500
          },
          step: 10,
          format: {
            to: function(value) {
              return "$" + $.fn.addCommas(Math.round(value));
            },
            from: function(value) {
              return value;
            }
          }
        });
      }

      if ($("#singleSlider").length > 0) {
        noUiSlider.create($("#singleSlider")[0], {
          start: 0,
          connect: true,
          tooltips: true,
          range: {
            min: 0,
            max: 150
          },
          step: 1,
          format: {
            to: function(value) {
              return $.fn.addCommas(Math.round(value));
            },
            from: function(value) {
              return value;
            }
          }
        });
      }
    }

    /* 03.24. Scrollbar */
    if (typeof PerfectScrollbar !== "undefined") {
      var chatAppScroll;

      $(".scroll").each(function() {
        if ($(this).parents(".chat-app").length > 0) {
          chatAppScroll = new PerfectScrollbar($(this)[0]);
          $(".chat-app .scroll").scrollTop(
            $(".chat-app .scroll").prop("scrollHeight")
          );
          chatAppScroll.update();
          return;
        }
        var ps = new PerfectScrollbar($(this)[0]);
      });
    }

    /* 03.25. Progress */
    $(".progress-bar").each(function() {
      $(this).css("width", $(this).attr("aria-valuenow") + "%");
    });

    if (typeof ProgressBar !== "undefined") {
      $(".progress-bar-circle").each(function() {
        var val = $(this).attr("aria-valuenow");
        var color = $(this).data("color") || themeColor1;
        var trailColor = $(this).data("trailColor") || "#d7d7d7";
        var max = $(this).attr("aria-valuemax") || 100;
        var showPercent = $(this).data("showPercent");
        var circle = new ProgressBar.Circle(this, {
          color: color,
          duration: 20,
          easing: "easeInOut",
          strokeWidth: 4,
          trailColor: trailColor,
          trailWidth: 4,
          text: {
            autoStyleContainer: false
          },
          step: (state, bar) => {
            if (showPercent) {
              bar.setText(Math.round(bar.value() * 100) + "%");
            } else {
              bar.setText(val + "/" + max);
            }
          }
        }).animate(val / max);
      });
    }

    /* 03.26. Rating */
    if ($().barrating) {
      $(".rating").each(function() {
        var current = $(this).data("currentRating");
        var readonly = $(this).data("readonly");
        $(this).barrating({
          theme: "bootstrap-stars",
          initialRating: current,
          readonly: readonly
        });
      });
    }

    /* 03.27. Tags Input */
    if ($().tagsinput) {
      $(".tags").tagsinput({
        cancelConfirmKeysOnEmpty: true,
        confirmKeys: [13]
      });

      $("body").on("keypress", ".bootstrap-tagsinput input", function(e) {
        if (e.which == 13) {
          e.preventDefault();
          e.stopPropagation();
        }
      });
    }

    /* 03.28. Sortable */
    if (typeof Sortable !== "undefined") {
      $(".sortable").each(function() {
        if ($(this).find(".handle").length > 0) {
          Sortable.create($(this)[0], { handle: ".handle" });
        } else {
          Sortable.create($(this)[0]);
        }
      });
      if ($(".sortable-survey").length > 0) {
        Sortable.create($(".sortable-survey")[0]);
      }
    }

    /* 03.29. State Button */
    $("#successButton").on("click", function(event) {
      event.preventDefault();
      var $button = $(this);
      if (
        $button.hasClass("show-fail") ||
        $button.hasClass("show-spinner") ||
        $button.hasClass("show-success")
      ) {
        return;
      }

      $button.addClass("show-spinner");
      $button.addClass("active");
      setTimeout(function() {
        $button.addClass("show-success");
        $button.removeClass("show-spinner");
        $button.find(".icon.success").tooltip("show");
        setTimeout(function() {
          $button.removeClass("show-success");
          $button.removeClass("active");
          $button.find(".icon.success").tooltip("dispose");
        }, 2000);
      }, 3000);
    });

    $("#failButton").on("click", function(event) {
      event.preventDefault();
      var $button = $(this);
      if (
        $button.hasClass("show-fail") ||
        $button.hasClass("show-spinner") ||
        $button.hasClass("show-success")
      ) {
        return;
      }

      $button.addClass("show-spinner");
      $button.addClass("active");
      setTimeout(function() {
        $button.addClass("show-fail");
        $button.removeClass("show-spinner");
        $button.find(".icon.fail").tooltip("show");
        setTimeout(function() {
          $button.removeClass("show-fail");
          $button.removeClass("active");
          $button.find(".icon.fail").tooltip("dispose");
        }, 2000);
      }, 3000);
    });


    /* 03.31. Full Screen */

    function isFullScreen() {
      var isInFullScreen =
        (document.fullscreenElement && document.fullscreenElement !== null) ||
        (document.webkitFullscreenElement &&
          document.webkitFullscreenElement !== null) ||
        (document.mozFullScreenElement &&
          document.mozFullScreenElement !== null) ||
        (document.msFullscreenElement && document.msFullscreenElement !== null);
      return isInFullScreen;
    }

    function fullscreen() {
      var isInFullScreen = isFullScreen();

      var docElm = document.documentElement;
      if (!isInFullScreen) {
        if (docElm.requestFullscreen) {
          docElm.requestFullscreen();
        } else if (docElm.mozRequestFullScreen) {
          docElm.mozRequestFullScreen();
        } else if (docElm.webkitRequestFullScreen) {
          docElm.webkitRequestFullScreen();
        } else if (docElm.msRequestFullscreen) {
          docElm.msRequestFullscreen();
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      }
    }

    $("#fullScreenButton").on("click", function(event) {
      event.preventDefault();
      if (isFullScreen()) {
        $($(this).find("i")[1]).css("display", "none");
        $($(this).find("i")[0]).css("display", "inline");
      } else {
        $($(this).find("i")[1]).css("display", "inline");
        $($(this).find("i")[0]).css("display", "none");
      }
      fullscreen();
    });


    if (typeof ClassicEditor !== "undefined") {
      ClassicEditor.create(document.querySelector("#ckEditorClassic")).catch(
        error => {}
      );
    }

    /* 03.33. Showing Body */
    $("body > *")
      .stop()
      .delay(100)
      .animate({ opacity: 1 }, 300);
    $("body").removeClass("show-spinner");
    $("main").addClass("default-transition");
    $(".sub-menu").addClass("default-transition");
    $(".main-menu").addClass("default-transition");
    $(".theme-colors").addClass("default-transition");

    /*03.34. Keyboard Shortcuts*/
    if (typeof Mousetrap !== "undefined") {
      //Go to next page on sub menu
      Mousetrap.bind(["ctrl+down", "command+down"], function(e) {
        var $nextItem = $(".sub-menu li.active").next();
        if ($nextItem.length == 0) {
          $nextItem = $(".sub-menu li.active")
            .parent()
            .children()
            .first();
        }
        window.location.href = $nextItem.find("a").attr("href");
        return false;
      });

      //Go to prev page on sub menu
      Mousetrap.bind(["ctrl+up", "command+up"], function(e) {
        var $prevItem = $(".sub-menu li.active").prev();
        if ($prevItem.length == 0) {
          $prevItem = $(".sub-menu li.active")
            .parent()
            .children()
            .last();
        }
        window.location.href = $prevItem.find("a").attr("href");
        return false;
      });

      //Go to next page on main menu
      Mousetrap.bind(["ctrl+shift+down", "command+shift+down"], function(e) {
        var $nextItem = $(".main-menu li.active").next();
        if ($nextItem.length == 0) {
          $nextItem = $(".main-menu li:first-of-type");
        }
        var $link = $nextItem
          .find("a")
          .attr("href")
          .replace("#", "");
        var $firstSubLink = $(
          ".sub-menu ul[data-link='" + $link + "'] li:first-of-type"
        );
        window.location.href = $firstSubLink.find("a").attr("href");
        return false;
      });

      //Go to prev page on main menu
      Mousetrap.bind(["ctrl+shift+up", "command+shift+up"], function(e) {
        var $prevItem = $(".main-menu li.active").prev();
        if ($prevItem.length == 0) {
          $prevItem = $(".main-menu li:last-of-type");
        }
        var $link = $prevItem
          .find("a")
          .attr("href")
          .replace("#", "");
        var $firstSubLink = $(
          ".sub-menu ul[data-link='" + $link + "'] li:first-of-type"
        );
        window.location.href = $firstSubLink.find("a").attr("href");
        return false;
      });

      /*Select all with ctrl+a and deselect all with ctrl+d at list pages */
      if ($(".list") && $(".list").length > 0) {
        Mousetrap.bind(["ctrl+a", "command+a"], function(e) {
          $(".list")
            .shiftSelectable()
            .data("shiftSelectable")
            .selectAll();
          return false;
        });

        Mousetrap.bind(["ctrl+d", "command+d"], function(e) {
          $(".list")
            .shiftSelectable()
            .data("shiftSelectable")
            .deSelectAll();
          return false;
        });
      }
    }

    /*03.35. Context Menu */
    if ($().contextMenu) {
      $.contextMenu({
        selector: ".list .card",
        callback: function(key, options) {
          var m = "clicked: " + key;
        },
        events: {
          show: function(options) {
            var $list = options.$trigger.parents(".list");
            if ($list && $list.length > 0) {
              $list.data("shiftSelectable").rightClick(options.$trigger);
            }
          }
        },
        items: {
          copy: {
            name: "Copy",
            className: "simple-icon-docs"
          },
          archive: { name: "Move to archive", className: "simple-icon-drawer" },
          delete: { name: "Delete", className: "simple-icon-trash" }
        }
      });
    }

    /* 03.36. Select from Library */
    if ($().selectFromLibrary) {
      $(".sfl-multiple").selectFromLibrary();
      $(".sfl-single").selectFromLibrary();
      /*
      Getting selected items
      console.log($(".sfl-multiple").selectFromLibrary().data("selectFromLibrary").getData());
      console.log($(".sfl-single").selectFromLibrary().data("selectFromLibrary").getData());
      */
    }
  }
  init();
};

$.fn.dore = function(options) {
  return this.each(function() {
    if (undefined == $(this).data("dore")) {
      var plugin = new $.dore(this, options);
      $(this).data("dore", plugin);
    }
  });
};
