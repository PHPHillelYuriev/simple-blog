$(document).ready(function() {
    $('.js-showCategories').on('click', function(e) {
        e.preventDefault();

        var $link = $(e.currentTarget);
        
        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function(data) {
            console.log(data);
            $('.js-categoriesList').append("<li>" + data.category1 + "</li>");
            $('.js-categoriesList').append("<li>" + data.category2 + "</li>");
            $('.js-categoriesList').append("<li>" + data.category3 + "</li>");
        });
    });
}); 