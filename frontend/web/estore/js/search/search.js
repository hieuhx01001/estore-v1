$(function () {

    function pageInit () {

        var curParams = getUrlParams();
        var selectedName = curParams['name'];
        var selectedCategoryId = curParams['category_id'];
        var selectedClass = 'selected';
        var selectedMinPrice = curParams['min_price'];
        var selectedMaxPrice = curParams['max_price'];

        $('#txt-name').val(selectedName);

        $('#category-list a').each(function (idx, item) {
            if ($(item).data('id') == selectedCategoryId) {
                $(item).addClass(selectedClass);
            }
        });

        $('#txt-min-price').val(selectedMinPrice);

        $('#txt-max-price').val(selectedMaxPrice);
    }

    /**
     * Build query string from the data selected by user,
     * then reload the page with the newly built query string
     */
    function search () {

        var params = {
            name: getProductName(),
            category_id: getSelectedCategoryId(),
            min_price: getMinPrice(),
            max_price: getMaxPrice()
        }

        params = $.extend({}, params);

        var url = getUrl();
        var queryString = $.param(params);

        // Reload the page with the newly built query string
        window.location = url + '?' + queryString;
    }

    /**
     * Get the current URL
     *
     * @returns {string}
     */
    function getUrl() {
        var url = location.protocol + '//' + location.host + location.pathname;
        return url;
    }

    /**
     * Return JSON object containing data collected from URL Params
     *
     * @returns {JSON}
     */
    function getUrlParams () {

        var params = {};
        var posQuestionSign = window.location.href.indexOf('?');
        var queryString;
        var hashes;
        var hash;

        // Question sign not exists on the URL...
        if (posQuestionSign == -1) {
            return {};
        }

        queryString = window.location.href.slice(posQuestionSign + 1);

        // The query string is empty...
        if (queryString == '') {
            return {};
        }

        hashes = queryString.split('&');

        for (var i in hashes) {
            hash = hashes[i].split('=');
            params[hash[0]] = hash[1];
        }

        return params;
    }

    /**
     * Return product name from search text box,
     * or undefined value if the text box is empty
     *
     * @returns {*|integer}
     */
    function getProductName () {

        var name = $('#txt-name').val().trim();

        if (! name) {
            name = undefined;
        }

        return name;
    }

    /**
     * Return the selected category ID,
     * or undefined value if no category is chosen
     *
     * @returns {*|integer}
     */
    function getSelectedCategoryId () {

        var id = $('#category-list a.selected').data('id');

        return id;
    }

    /**
     * Return the min price from text box,
     * or undefined value if the text box is empty
     *
     * @returns {*|integer}
     */
    function getMinPrice () {

        var minPrice = $('#txt-min-price').val().trim();

        if (! minPrice) {
            minPrice = undefined;
        }

        return minPrice;
    }

    /**
     * Return the max price from text box,
     * or undefined value if the text box is empty
     *
     * @returns {*|integer}
     */
    function getMaxPrice () {

        var maxPrice = $('#txt-max-price').val().trim();

        if (! maxPrice) {
            maxPrice = undefined;
        }

        return maxPrice;
    }

    $('#searchform').submit(function (evt) {

        evt.preventDefault();

        search();
    });

    $('#category-list a').click(function (evt) {

        evt.preventDefault();

        var thisBtn = $(this);
        var selectedClass = 'selected';

        if (thisBtn.hasClass(selectedClass)) {
            thisBtn.removeClass(selectedClass);
        }
        else {
            $('#category-list a.selected').removeClass(selectedClass);
            thisBtn.addClass(selectedClass);
        }

        search();
    });

    $('#frm-price-range').submit(function (evt) {

        evt.preventDefault();

        search();
    })


    /*******************************
     *    RUN PAGE-INIT at START   *
     *******************************/

    pageInit();

});
