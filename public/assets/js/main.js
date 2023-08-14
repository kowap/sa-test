function isURL(str) {
  // Регулярное выражение для проверки URL
  var urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/;

  return urlPattern.test(str);
}
$(document).ready(function (){
  let body = $('body');

  var options = {
    classname: 'my-class',
    id: 'my-id',
    target: document.getElementById('myDivId')
  };

  var nanobar = new Nanobar( options );

  body.on('keyup', '#product_store_link', function (el) {
    el.preventDefault();
    if (isURL($(this).val())) {
      nanobar.go( 65 );
    }

    $.post('/parse', {'url': $(this).val()})
      .done(response => {
        if (response.length !== 0) {
          $('#product_name').val(response.name);
          $('#product_description').val(response.description);
          $('#product_price').val(response.price);
          $('#product_photo').val(response.image);
          $('.product-photo').attr('src', '/uploads/photo/' + response.image)
          nanobar.go( 100 );
        }
      });
  });
});


