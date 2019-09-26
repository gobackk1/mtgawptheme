(function ($) {
  $(function () {
    'use strict';
    $('.Main-slider').slick({
      autoplay: true,
      autoplaySpeed: 4000,
      dots: true,
      arrows: false,
      draggable: false,
      infinite: true,
      pauseOnHover: false,
      pauseOnFocus: false,
      fade: true,
      speed: 1000
    });

    // --------- スクロールに応じてonをつける

    var $body = $('body');
    var $targetElements = $body.find('.Online,.Planes,.Cards');
    var $targetElementsLength = $targetElements.length;
    var timer = 0;
    var scrollClassModifier = "on";
    var $winHeight = $(window).height();

    function scrollAddName(section, targetOffsetTop, name) {
      if ($(window).scrollTop() + $winHeight * 4 / 5 > targetOffsetTop) {
        $(section).addClass(name);
      } else {
        $(section).removeClass(name);
      }
    }

    function setAddNames() {
      var targetElementsOT = [];
      for (var i = 0; i < $targetElementsLength; i++) {
        var offsetTop = $targetElements.eq(i).offset().top;
        targetElementsOT.push(offsetTop);
        scrollAddName($targetElements[i], targetElementsOT[i], scrollClassModifier);
      }
    }

    function debounceFunc(func, event) {
      if (typeof event === "number") {
        var timeOffset = event;
      } else if (event.data) {
        var timeOffset = event.data.timeOffset;
      } else {
        return;
      }
      if (timer > 0) {
        clearTimeout(timer);
      }
      timer = setTimeout(func, timeOffset);
    }

    function onScrollAddNames(event) {
      debounceFunc(setAddNames, event);
    }

    $(window).on('scroll', { timeOffset: 30 }, onScrollAddNames);
    $(window).on('resize', { timeOffset: 200 }, onScrollAddNames);

    // --------- drawer

    var $html = $('html');
    var $openBtn = $body.find($('.Open-btn'));
    var $closeBtn = $body.find($('.Close-btn'));
    var $drawer = $body.find($('.Drawer'));
    var $drawerOverlay = $body.find($('.Drawer__overlay'));
    var drawerOpen = false; //ドロワーが開いてたらtrue
    var $scrollbarFixTargets = $('.scrollbarFix');
    var scrollbarFix = false;
    var scrollLockModifier = 'drawerOpen';
    var tabbableElements = $drawer.find('a[href],button:not(:disabled)');
    var firstTabbable = tabbableElements[0];
    var lastTabbable = tabbableElements[tabbableElements.length - 1];

    function changeAriaExpanded(state) {
      var value = state ? 'true' : 'false';
      $drawer.attr('aria-expanded', value);
      $openBtn.attr('aria-expanded', value);
      $closeBtn.attr('aria-expanded', value);
    }

    function changeState(state) {
      if (state === drawerOpen) {
        return;
      }
      changeAriaExpanded(state);
      drawerOpen = state;
    }

    function openDrawer() {
      changeState(true);
    }

    function closeDrawer() {
      changeState(false);
    }

    function addScrollbarWidth() {
      var scrollbarWidth = window.innerWidth - $(window).width();
      if (!scrollbarWidth) {
        scrollbarFix = false;
        return;
      }
      addScrollbarMargin(scrollbarWidth);
      scrollbarFix = true;
    }

    function removeScrollbarWidth() {
      if (!scrollbarFix) {
        return;
      }
      addScrollbarMargin(0);
    }

    function addScrollbarMargin(value) {
      $scrollbarFixTargets.css('margin-right', value + "px")
    }

    function activateScrollLock() {
      addScrollbarWidth();
      $html.addClass(scrollLockModifier);
    }

    function deactivateScrollLock() {
      removeScrollbarWidth();
      $html.removeClass(scrollLockModifier);
    }

    function onClickOpenBtn() {
      openDrawer();
      activateScrollLock();
    }

    function onClickCloseBtn() {
      closeDrawer();
      deactivateScrollLock();
      $openBtn.focus();
    }

    function onKeydownTabKeyFirstTabbable(event) {
      if (event.key !== "Tab" || !event.shiftKey) {
        return;
      }
      event.preventDefault();
      lastTabbable.focus();
    }

    function onKeydownTabKeyLastTabbable(event) {
      if (event.key !== "Tab" || event.shiftKey) {
        return;
      }
      event.preventDefault();
      firstTabbable.focus();
    }

    function onKeydownEsc(event) {
      if (!drawerOpen || event.key !== "Escape") {
        return;
      }
      event.preventDefault();
      onClickCloseBtn()
    }

    $(window).on('keydown', onKeydownEsc);
    $(firstTabbable).on('keydown', onKeydownTabKeyFirstTabbable);
    $(lastTabbable).on('keydown', onKeydownTabKeyLastTabbable);
    $openBtn.on('click', onClickOpenBtn);
    $closeBtn.on('click', onClickCloseBtn);
    $drawerOverlay.on('click', onClickCloseBtn);

    // --------- 特定のUAの場合bodyにクラスをつける

    var isIOS = /iP(hone|od|ad)/.test(navigator.userAgent);
    if (isIOS) {
      $body.addClass('ios');
    }

    // --------- pagetop button

    var $pagetop = $body.find('.Pagetop');
    var pagetopThresholdBase = 300;

    $pagetop.hide();

    function onScrollFadePagetop() {
      if (pagetopThresholdBase < $(this).scrollTop()) {
        $pagetop.fadeIn();
      } else {
        $pagetop.fadeOut();
      }
    }

    $(window).on('scroll', onScrollFadePagetop);

    // --------- スムーズスクロール

    var $anchors = $('a[href^="#"]a');
    var $scrollElements = $('body,html');

    $anchors.click(function () {
      var speed = 400;
      var href = $(this).attr("href");
      var target = $(href == "#" || href == "" ? 'html' : href);
      var position = target.offset().top;
      $scrollElements.animate({ scrollTop: position }, speed, 'swing');
      history.pushState(null, null, href);
      return false;
    });

  });
})(jQuery);
