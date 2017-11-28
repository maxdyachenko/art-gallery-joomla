jQuery(function () {
    document.formvalidator.setHandler('name', function (value) {
        var regex = /^[A-Za-z]{2,16}$/;
        return regex.test(value);
    })
})