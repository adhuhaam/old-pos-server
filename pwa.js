(function () {
  var promptEvent = null;

  function scriptBase() {
    var script = document.currentScript;
    if (script && script.src) {
      return new URL('./', script.src);
    }
    return new URL('./', window.location.href);
  }

  function standalone() {
    return window.matchMedia('(display-mode: standalone)').matches
      || window.matchMedia('(display-mode: fullscreen)').matches
      || window.navigator.standalone === true;
  }

  document.addEventListener('gesturestart', function (event) {
    event.preventDefault();
  }, { passive: false });

  document.addEventListener('touchmove', function (event) {
    if (event.touches && event.touches.length > 1) {
      event.preventDefault();
    }
  }, { passive: false });

  function showButtons() {
    if (!promptEvent || standalone()) {
      return;
    }
    document.querySelectorAll('#pwa-install').forEach(function (button) {
      button.hidden = false;
      button.onclick = function (event) {
        event.preventDefault();
        window.pwaInstall();
      };
    });
  }

  window.pwaInstalled = function () {
    return standalone();
  };
  window.pwaCanInstall = function () {
    return !!promptEvent && !standalone();
  };
  window.pwaInstall = function () {
    if (!promptEvent) {
      return Promise.resolve(null);
    }
    var pending = promptEvent;
    promptEvent = null;
    pending.prompt();
    return pending.userChoice.finally(function () {
      document.querySelectorAll('#pwa-install').forEach(function (button) {
        button.hidden = true;
      });
    });
  };

  window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    promptEvent = event;
    window.dispatchEvent(new Event('pwa-installable'));
    showButtons();
  });

  window.addEventListener('appinstalled', function () {
    promptEvent = null;
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', showButtons);
  } else {
    showButtons();
  }

  if (!('serviceWorker' in navigator)) {
    return;
  }

  var base = scriptBase();
  navigator.serviceWorker.getRegistrations().then(function (regs) {
    regs.forEach(function (reg) {
      var worker = reg.active || reg.waiting || reg.installing;
      var url = worker ? worker.scriptURL : '';
      if (url.indexOf('/waiter/sw.js') !== -1) {
        reg.unregister();
      }
    });
  });
  navigator.serviceWorker.register(new URL('sw.js', base).href, { scope: base.href }).catch(function () {});
})();
