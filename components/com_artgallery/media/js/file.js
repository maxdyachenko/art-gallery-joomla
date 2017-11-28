jQuery(function () {
    document.formvalidator.setHandler('file', function (value) {
        var regex = /^.*\.(jpg|JPG|png|PNG)$/;
        return regex.test(value);
    })
})