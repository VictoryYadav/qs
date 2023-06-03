const ajaxCall = (url, method, data, handleData) => {
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: 'json',
        beforeSend: function() {
            console.log('show loader');
            $('.loder_bg').css('display','flex');
        },
        success: function(response) {
            handleData(response);
            // alert("Aaaa");
            $('.loder_bg').css('display','none');
        },
        error: (xhr, status, error) => {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    })
}