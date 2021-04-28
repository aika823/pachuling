$(function(){
    var sjModal = function(){
        $body = $('body');
        $modalWrap = $('.modal_wrap');

        $('.open_modal').on('click',function(){
            $body.addClass('modal_in');
            $modalWrap.fadeIn(300);
            $body.css('padding-right',getScrollWidth());
            $('.help-title').text($(this).closest('div').children('h2').text())
        });

        $('.close_btn, .modal_dim').on('click',function(){
            var speed = 300;
            $modalWrap.fadeOut(speed);
            setTimeout(function(){
                $body.removeClass('modal_in');
                $body.css('padding-right',0)
            },speed)
        });

        function getScrollWidth(){
            var body = document.querySelector('body');
            var scrollDiv = document.createElement('div');
                scrollDiv.className = 'fake_sjwidth';
                body.appendChild(scrollDiv);
            var scrollbarWidth = $(document).height() > $(window).height() ? scrollDiv.offsetWidth - scrollDiv.clientWidth : 0;

                body.removeChild(scrollDiv);
                return scrollbarWidth;
        }


    }();


});