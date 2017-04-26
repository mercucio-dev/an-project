$( document ).ajaxComplete(function() {
    $('.popup-json').click(function() {
      var file = $(this).attr('data');
      var items = [];
        $.ajax({
          type: "GET",
          url: file,
          dataType: 'json',
          success: function(data){
            for( var i in data.gallery_items ) {
              items.push({
                src: data.gallery_items[i].url,
                title: data.gallery_items[i].title,
                type: data.gallery_items[i].type
              });
            };
            $('#postModal').modal('hide');
            $.magnificPopup.open({
                                  items: items,
                                  //type: 'image',
                                  gallery: { enabled: true },
                                  callbacks: {
                                            close: function(){
                                              $('#postModal').modal('show');
                                            }
                                  }
                                })
            }
          })
    });
});
// End section after ajax

$(document).ready(function($) {
    fillin();
    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
/* */
      $('map').imageMapResize();

    $('.carousel').carousel({
        interval: 3000
    });

    $('.btnModalStart').click(function(e) {
        e.preventDefault();
        $('#postModal').modal('show');

        var elements = document.querySelectorAll('.anim-post')
        var animationDuration = 1000; // in milliseconds

        for (var i = 0; i < elements.length; i++) {
            var randomDuration = Math.floor(Math.random() * animationDuration);
            elements[i].style.animationDelay = randomDuration + 'ms';
        }

        $('.anim-post').addClass('animated bounceInLeft').one(animationEnd, function() {
            $('.anim-post').removeClass('animated bounceInLeft');
        });
    });

    $('#napisz_do_mnie').click(function() {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $('#kontakt').addClass('animated bounceInRight kontakt_anim').one(animationEnd, function() {
            $('#kontakt').removeClass('animated bounceInRight');
        });
    });

    $('#button_cofnij').click(function() {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $('#kontakt').addClass('animated bounceOutRight').one(animationEnd, function() {
            $('#kontakt').removeClass('animated bounceOutRight kontakt_anim');
        });
        $('#eml').val("");
        $('#comment').val("");
    });

    $('#button_send').click(function() {

      if (!$("#contactForm").valid()) return false;

        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $('#envelope_gone').addClass('kontakt_anim fadeIn');
        $('#kontakt').addClass('animated bounceOutRight').one(animationEnd, function() {
        $('#kontakt').removeClass('animated bounceOutRight kontakt_anim');
        $('#envelope_gone').addClass('animated rotateOut').one(animationEnd, function() {
            $('#envelope_gone').removeClass('animated rotateOut kontakt_anim fadeIn');
            });
        });
        $('#eml').val("");
        $('#comment').val("");
    });

      $("#contactForm").validate({
        rules: {
          message: "required",
          email: {
          required: true,
          email: true
            }
          },
        messages: {
          message: "Wpisz swoją wiadomość",
          email: {
            required: "musisz podać prawidłowy adres email",
            email: "niepoprawny adres"
            }
          }
        });
});

// fill in modal function
// $("#modal-id")
function fillin() {
  var postsList = './posts/posts.json';
  var posts = [];
  $.ajax({
    type: "GET",
    url: postsList,
    //async:false,
    dataType: 'json',
    success: function(data){
      for( var i in data ) {
        posts.push({
          link: data[i].link,
          img: data[i].img,
          alt: data[i].alt
        });
      }
      for(var j in posts) {
        $('#modal-id').append(
          '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-1 anim-post">'+
          '<a href="#" class="thumbnail popup-json" data="'+posts[j].link+'">'+
          '<img src = "'+posts[j].img+'" alt="'+posts[j].alt+'"/></a></div>');
          // console.log('<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-1 anim-post">');
          // console.log('<a href="#" class="thumbnail popup-json" data="'+posts[j].link+'">');
          // console.log('<img src = "'+posts[j].img+'" alt="'+posts[j].alt+'"/></a></div>');
      }
    },
    complete: $('#modal-id').show(),
    error: function(err){
      console.log("error: can't load ajax content");
    }
  });
  // $('#modal-id').click(function(e){
  //   e.preventDefault();
  //   $(this).parent().next('#modal-id').toggle();
  // });
};
//end fillin function
