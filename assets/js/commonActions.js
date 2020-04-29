$(document).ready(() => {

    $('.navShowHide').on('click', () => {
        
        const main = $('#mainSectionContainer');
        const nav = $('#sideNavContainer');

        if (main.hasClass('leftPadding')) {
            nav.hide();
        } else {
            nav.show();
        }

        main.toggleClass('leftPadding');

    });

});