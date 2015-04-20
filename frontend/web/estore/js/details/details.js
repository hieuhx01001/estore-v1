$(function () {
    // Highlight the current category
    var currentCategoryId = window.currentCategoryId;
    $('#category-list a[data-id="' + currentCategoryId + '"]').addClass('selected');
    console.log($('#category-list a[data-id="' + currentCategoryId + '"]').size());
});