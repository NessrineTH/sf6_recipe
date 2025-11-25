var search_panel = document.getElementsByClassName('settings js-settings');
const arr_search_panel = Array.from(search_panel);
var search_btn = document.querySelectorAll('.js-settings-toggle');
search_btn.forEach((function (n) {
    n.onclick = function (n) {
        n.preventDefault();

        arr_search_panel.forEach(function (el) {
            el.classList.toggle('open');
            document.getElementById('quick_search_input').focus();
        })

    }
}))

document.body.onclick = function (n) {
    var isBtnSearch = false;
    search_btn.forEach(function (sp) {

        isBtnSearch = isBtnSearch || sp.contains(n.target);
    });
    arr_search_panel.forEach(function (el) {
        isBtnSearch ||
        el.contains(n.target) || el.classList.remove('open');
    })
}
var query = '';
var input = document.querySelector('#quick_search_input');
var form = document.querySelector('#quick_search_form');
var resultWrapper = document.querySelector( '.settings-panel .quick-search__wrapper');


var minLength = 3;
var isProcessing = false;
var timeout = false;
var requestTimeout = 50;
var hasResult = false;



var showProgress = function() {
    isProcessing = true;
}

var hideProgress = function() {
    isProcessing = false;

}


form.onkeypress = function(e) {
    var key = e.charCode || e.keyCode || 0;
    if (key == 13) {
        e.preventDefault();
    }
}

input.onkeyup = function () {
    handleSearch();
};
input.onfocus = function () {
    handleSearch();
};

var handleSearch = function () {
    if (input.value.length < minLength) {
        hideProgress();
       // hideDropdown();

        return;
    }

    if (isProcessing == true) {
        return;
    }

    if (timeout) {
        clearTimeout(timeout);
    }

    timeout = setTimeout(function () {
        processSearch();
    }, requestTimeout);

};
var processSearch = function () {
    if (hasResult && query === input.value) {
        hideProgress();
        return;
    }

    query = input.value;
    showProgress();
    //setTimeout(function () {
        $.ajax({
            url: $('#admsearchbtn').data('url'),
            data: {
                query: query
            },
            dataType: 'html',
            success: function (res) {
                hasResult = true;
                hideProgress();
                resultWrapper.innerHTML = res;
            },
            error: function () {
                hasResult = false;
                hideProgress();
                resultWrapper.innerHTML = '<span class="quick-search__message">aucun resultat.</div>';
            }
        });
    //}, 1000);
};
