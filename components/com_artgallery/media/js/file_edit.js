jQuery(function () {
    var fileInput = document.getElementById('file'),
        label = document.getElementsByTagName('label')[0];
    fileInput.addEventListener('change', function(e){
        fileName = e.target.value.split('\\').pop();
        label.querySelector('p').innerHTML = fileName;
    });

});
