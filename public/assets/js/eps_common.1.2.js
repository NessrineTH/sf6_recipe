// Version 1.2

const eps_common = {

  mainModal: null,

  attributeActions: {},

  // Fonctions avec appel par attribut

  formModalRefreshGrid: function (event) {
    this.formModal(event, '', '', 'grid');
  },

  formModalRefresHtmlElement: function (event) {
    const selector = event.currentTarget.getAttribute('data-eps-selector');
    this.formModal(event, '', selector, 'html_element');
  },

  formModalRefresHtmlElements: function (event) {
    this.formModal(event, '', '', 'html_elements');
  },

  formModalAjaxRefreshZone: function (event) {
    const urlrefresh = event.currentTarget.getAttribute('data-eps-urlrefresh');
    const selector = event.currentTarget.getAttribute('data-eps-selector');
    this.formModal(event, '', function () { eps_common.refreshZone(event, urlrefresh, selector) }, 'callback');
  },

  formModalRedirToVal: function (event) {
    this.formModal(event, '', '', 'redir_to_val');
  },

  formModalReloadLocation: function (event) {
    this.formModal(event, '', '', 'reload_location');
  },

  ajaxRefreshElement: function (event) {
    const urlaction = event.currentTarget.getAttribute('data-eps-urlaction');
    const selector = event.currentTarget.getAttribute('data-eps-selector');
    this.refreshZone(event, urlaction, selector);
  },

  preventDblClick: function (event) {
    const ms = event.currentTarget.getAttribute('data-eps-millisec') || 100;
    const $el = $(event.currentTarget);
    $el.prop('disabled', true);
    $el.addClass('disabled', true);
    setTimeout(() => {
      $el.prop('disabled', false);
      $el.removeClass('disabled');
    }, ms);
  },

  showAlert(event) {

    const title = event.currentTarget.getAttribute('data-eps-title') || '';
    const description = event.currentTarget.getAttribute('data-eps-description') || '';

    event.preventDefault();

    if (this.mainModal !== null) {
      this.mainModal.hide();
    }
    var uniqid = Date.now();
    var orig = document.getElementById('eps_popup_alert');
    var mainModal = orig.cloneNode(true);
    mainModal.id = 'eps_popup_alert_' + uniqid;
    mainModal.querySelector('.modal-title').innerHTML = title;
    mainModal.querySelector('.description').innerHTML = description;
    document.body.append(mainModal);
    this.mainModal = new bootstrap.Modal(mainModal);
    this.mainModal.show();
  },

  confirmOuiNonGotoUrl(event) {

    let urlaction = event.currentTarget.getAttribute('data-eps-urlaction');
    const title = event.currentTarget.getAttribute('data-eps-title') || '';
    const description = event.currentTarget.getAttribute('data-eps-description') || '';

    urlaction += (urlaction.split('?')[1] ? '&' : '?') + 'md_user_confirm=1';

    this.confirmOuiNonCallback(event, function () { document.location = urlaction; }, title, description);
  },

  // Fonctions de base avec appel normal

  confirmOuiNonCallback(event, callback, title, description) {

    event.preventDefault();
    if (this.mainModal !== null) {
      this.mainModal.hide();
    }
    var uniqid = Date.now();
    var orig = document.getElementById('eps_popup_confirm');
    var mainModal = orig.cloneNode(true);
    mainModal.id = 'eps_popup_confirm_' + uniqid;
    mainModal.querySelector('.modal-success-btn').onclick = callback;
    if (title) {
      mainModal.querySelector('.modal-title').innerHTML = title;
    }
    if (description) {
      mainModal.querySelector('.description').innerHTML = description;
    }
    document.body.append(mainModal);
    this.mainModal = new bootstrap.Modal(mainModal);
    this.mainModal.show();
  },

  initSimpleFormModal: function (whatToRefresh, whatToRefreshType) {

    if (undefined === whatToRefresh) {
      whatToRefresh = '';
    }
    if (undefined === whatToRefreshType) {
      whatToRefreshType = '';
    }

    $('#form_modal').ajaxForm({
      success: function (data, textStatus) {
        if (data.RES == "OK") {
          eps_common.mainModal.hide();
          if (whatToRefreshType) {
            if ('grid' == whatToRefreshType) {
              refreshGrid();
            }
            if ('callback' == whatToRefreshType) {
              whatToRefresh(data, textStatus);
            }
            if ('html_element' == whatToRefreshType) {
              $(whatToRefresh).html(data.VAL);
            }
            if ('html_elements' == whatToRefreshType) {
              $.each(data.VAL, function (i, v) {
                $(whatToRefresh + i).html(v);
              });
            }
            if ('redir_to_val' == whatToRefreshType) {
              document.location = data.VAL;
            }
            if ('reload_location' == whatToRefreshType) {
              document.location.reload();
            }
          }
          if (undefined != data.RELOAD_URL) {
            document.location = data.RELOAD_URL;
          }
        } else {
          let elm = document.getElementById('eps_popup');
          elm.innerHTML = data.HTML;
          eps_common.initSimpleFormModal(whatToRefresh, whatToRefreshType);
        }
      }
    });

    $(window).trigger('initSimpleForm:contentloaded');

    let elm = document.getElementById('eps_popup');
    //execution des scripts
    Array.from(elm.querySelectorAll("script")).forEach(oldScript => {
      const newScript = document.createElement("script");
      Array.from(oldScript.attributes)
        .forEach(attr => newScript.setAttribute(attr.name, attr.value));
      newScript.appendChild(document.createTextNode(oldScript.innerHTML));
      oldScript.parentNode.replaceChild(newScript, oldScript);
    });

  },

  formModal: function (event, urlaction, whatToRefresh, whatToRefreshType, callbackAfterShow, postInitData) {
    event.preventDefault();

    if ('' === urlaction) {
      urlaction = event.currentTarget.getAttribute('data-eps-urlaction');
    }
    if (undefined === callbackAfterShow) {
      callbackAfterShow = event.currentTarget.getAttribute('data-eps-callback-after-show');
    }
    if (undefined === postInitData) {
      postInitData = event.currentTarget.getAttribute('data-eps-post-init-data');
    }

    if (this.mainModal !== null) {
      //eps_common.mainModal.dispose();
      // sinon empile les fonds
      this.mainModal.hide();
    }

    if (undefined === postInitData || null === postInitData) {
      method = 'GET';
      postInitData = false;
    }
    else {
      method = 'POST';
    }

    jQuery.ajax({
      type: method,
      dataType: 'json',
      data: postInitData,
      success: function (data, textStatus) {
        let elm = document.getElementById('eps_popup');
        elm.innerHTML = data.HTML;
        eps_common.initSimpleFormModal(whatToRefresh, whatToRefreshType);
        var options = {};
        eps_common.mainModal = new bootstrap.Modal(document.getElementById('eps_popup'), options);
        eps_common.mainModal.show();
        if ((callbackAfterShow !== undefined) && (callbackAfterShow !== null) && (typeof callbackAfterShow === 'function')) {
          callbackAfterShow(data, textStatus);
        }
        //
        var modalEl = document.getElementById('eps_popup')
        modalEl.addEventListener('shown.bs.modal', function (event) {
          $(this).find('input:not([readonly]):text:not([readonly]):visible,textarea:visible').first().focus();
          autosize(modalEl.querySelectorAll('textarea'));
          autosize.update(modalEl.querySelectorAll('textarea'));
        });
        //  
      },
      url: urlaction
    });
  },

  hideMainModal() {
    if (this.mainModal !== null) {
      this.mainModal.hide();
    }
  },

  showModal: function (event, urlaction, eps_popup_elt, callbackAfterShow, postInitData, callbackOnBtnSuccess) {

    if (event) {
      event.preventDefault();
    }

    if (undefined === urlaction) {
      urlaction = event.currentTarget.getAttribute('data-eps-urlaction');
    }

    if (this.mainModal !== null) {
      this.mainModal.hide();
    }

    if (undefined === eps_popup_elt) {
      eps_popup_elt = 'eps_popup';
    }
    if (undefined === postInitData) {
      method = 'GET';
      postInitData = false;
    }
    else {
      method = 'POST';
    }

    jQuery.ajax({
      type: method,
      dataType: 'html',
      data: postInitData,
      success: function (data, textStatus) {
        let elm = document.getElementById(eps_popup_elt);
        elm.innerHTML = data;

        //execution des scripts
        Array.from(elm.querySelectorAll("script")).forEach(oldScript => {
          const newScript = document.createElement("script");
          Array.from(oldScript.attributes)
            .forEach(attr => newScript.setAttribute(attr.name, attr.value));
          newScript.appendChild(document.createTextNode(oldScript.innerHTML));
          oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        var options = {};
        eps_common.mainModal = new bootstrap.Modal(document.getElementById(eps_popup_elt), options);
        eps_common.mainModal.show();
        var modalEl = document.getElementById(eps_popup_elt)
        modalEl.addEventListener('shown.bs.modal', function (event) {
          autosize(modalEl.querySelectorAll('textarea'));
          autosize.update(modalEl.querySelectorAll('textarea'));
        });
        if ((callbackAfterShow !== undefined) && (typeof callbackAfterShow === 'function')) {
          callbackAfterShow();
        }
        elSucces = elm.querySelector('.modal-success-btn');
        if (elSucces) {
          elSucces.onclick = callbackOnBtnSuccess;
        }
      },
      url: urlaction
    });
  },

  refreshZone(event, rurl, selector) {
    jQuery.ajax({
      type: 'GET',
      dataType: 'html',
      success: function (data, textStatus) {
        $(selector).html(data);
      },
      url: rurl
    });
  }

}
// eps_common - fin



// Fonction de gestionnaire d'événements pour les liens personnalisés
function handleAction(event) {
  event.preventDefault();
  //console.log(event.currentTarget);
  /*event.stopPropagation();*/
  const action = event.currentTarget.getAttribute('data-eps-action');
  const actionFunction = eps_common.attributeActions[action];
  if (actionFunction && typeof actionFunction === 'function') {
    actionFunction.call(eps_common, event);
  } else {
    console.log("Action inconnue " + action);
  }
}


// Inscrit les action par attribut
eps_common.attributeActions = {
  'confirmOuiNonGotoUrl': eps_common.confirmOuiNonGotoUrl,
  'formModalRefreshGrid': eps_common.formModalRefreshGrid,
  'formModalRefresHtmlElement': eps_common.formModalRefresHtmlElement,
  'formModalRefresHtmlElements': eps_common.formModalRefresHtmlElements,
  'formModalAjaxRefreshZone': eps_common.formModalAjaxRefreshZone,
  'formModalRedirToVal': eps_common.formModalRedirToVal,
  'formModalReloadLocation': eps_common.formModalReloadLocation,
  'ajaxRefreshElement': eps_common.ajaxRefreshElement,
  'showModal': eps_common.showModal,
};

// Fonction pour initialiser les événements des liens personnalisés
function initEpsCommonAttributeActions() {
  // Autorsize textarea
  autosize(document.querySelectorAll('textarea:not(.noautosize)'));

  // Sélectionnez tous les éléments ayant la classe 'eps-common-onclick'
  const customLinks = document.querySelectorAll('.eps-common-onclick');

  // Attacher l'écouteur d'événements à chaque lien
  customLinks.forEach(link => {
    if (!link.hasAttribute('data-eps-initialized')) {
      link.addEventListener('click', handleAction);
      link.setAttribute('data-eps-initialized', true); // Marquer l'élément comme initialisé
    }
  });

}

// Appeler la fonction initEpsCommonAttributeActions lors du chargement initial de la page
document.addEventListener('DOMContentLoaded', initEpsCommonAttributeActions);

// init après le rechargement AJAX
function refreshAjaxContent() {
  initEpsCommonAttributeActions();
}

// init après le rechargement AJAX Jquery
$(document).ajaxComplete(function () {
  initEpsCommonAttributeActions();
});