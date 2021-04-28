$(function(){
    var sjModal = function(){
        $body = $('body');
        $modalWrap = $('.modal_wrap');

        $('.open_modal').on('click',function(){
            $body.addClass('modal_in');
            $modalWrap.fadeIn(300);
            $body.css('padding-right',getScrollWidth());
            $('.help-title').text($(this).closest('div').children('h2').text())
            for(var i=0; i<help.call.title.length; i++){
                if($('.help-title').text() === help.call.title[i]){
                    $('.help-decription').text(help.call.description[i]);
                }
            }
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

        var help = {
            "call" : {
                "title" : [
                    "상호명", "구직자 성명", "근무요일", "근무기간", "근무시간", "임금", "업종", "기타요청사항"
                ],
                "description" : [
                    "콜을 요청한 거래처의 상호명을 입력해주세요. 기존에 파출링을 통해 거래처 등록을 하지 않은 거래처를 등록할 경우, \
                    거래처와 구직자 목록을 통해 조회가 되지 않습니다.\
                    신규 거래처의 경우 거래처 목록에서 거래처 추가를 먼저 진행하고 콜 만들기를 진행해주세요.\
                    ",
                    "요청된 콜을 담당할 구직자의 상호명을 입력해주세요. 기존에 파출링을 통해 해당 구직자를 등록하지 않았을 경우, \
                    구직자 목록을 통해 조회가 되지 않습니다.\
                    신규 구직자의 경우 구직자 목록에서 구직자 추가를 먼저 진행하고 콜 만들기를 진행해주세요."
                ],
                "source" : [
        
                ]
            },
            "company" : {
        
            },
            "employee" : {
                
            }
        }


    }();


});