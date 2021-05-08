$(function(){
    var sjModal = function(){
        $body = $('body');
        $modalWrap = $('.modal_wrap');

        function helpInfo(info, open){
            $('.help-title').text($(open).closest('div').children('h2').text());
            for(var i=0; i<info.title.length; i++){
                if($(open).closest('div').children('h2').text() === info.title[i]){
                    var obj = $('.help-decription').text(info.description[i]);;
                    obj.html(obj.html().replace(/\n/g,'<br/>'));
                    $('.help-img').attr("src", info.source[i]);
                }
            }
        }

        $('.open_modal').on('click',function(){
            $body.addClass('modal_in');
            $modalWrap.fadeIn(300);
            $body.css('padding-right',getScrollWidth());

            if($('.modal_wrap').closest('div').attr('id') == "call-help"){
                helpInfo(help.call, this);
            } else if($('.modal_wrap').closest('div').attr('id') == "company-help"){
                helpInfo(help.company, this);
            } else if($('.modal_wrap').closest('div').attr('id') == "employee-help"){
                helpInfo(help.employee, this);
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
                    "상호명", "구직자 성명", "근무요일", "근무기간", "근무시간", "임금", "업종", "기타 요청 사항"
                ],
                "description" : [
                    "\
                    콜을 요청한 거래처의 상호명을 입력해주세요. \n 기존에 파출링을 통해 거래처 등록을 하지 않은 거래처를 등록할 경우,\n \
                    거래처와 구직자 목록을 통해 조회가 되지 않습니다.\n\n \
                    신규 거래처의 경우 거래처 목록에서 거래처 추가를 먼저 진행하고 콜 만들기를 진행해주세요. \
                    ",
                    "\
                    요청된 콜을 담당할 구직자의 이름을 입력해주세요. \n \
                    구직자를 선택하지 않고 콜을 등록하더라도 \n '콜관리' 페이지에서 '배정하기' 버튼을 통해 구직자를 선택할 수 있습니다. \n\n\
                    기존에 파출링을 통해 해당 구직자를 등록하지 않았을 경우, \n \
                    구직자 목록을 통해 조회가 되지 않습니다.\n \
                    신규 구직자의 경우 구직자 목록에서 구직자 추가를 먼저 진행하고 콜 만들기를 진행해주세요.\
                    ",
                    "\
                    요청된 콜에 대해 구직자가 근무할 요일을 선택해주세요. \n 중복 선택이 가능하며, 선택된 요일에 따라 배정가능한 구직자가 달라집니다.\
                    ",
                    "\
                    구직자가 근무할 기간을 입력해주세요.\
                    ",
                    "\
                    구직자가 근무할 시간을 입력해주세요.\
                    ",
                    "\
                    해당 콜에 대해 구직자에게 지불할 임금을 입력해주세요.\
                    ",
                    "\
                    콜을 요청하는 업체의 업종을 입력해주세요. '설거지', '주방보조', '홀서빙'의 경우 버튼만 클릭하면 빠르게 선택할 수 있습니다. \n \
                    다른 업종의 경우 버튼 아래의 선택창에서 업종을 선택해주세요. 선택창에 해당되는 업종이 없다면, '기타'를 선택해주시면 됩니다.\
                    ",
                    "\
                    콜 요청시 추가적인 요청사항을 입력해주세요. 예) '남성분으로 보내주세요.', '50대 여성으로 부탁드립니다.'\
                    "
                ],
                "source" : [
                    "/static/img/help_images/call_src1.png",
                    "/static/img/help_images/call_src2.png",
                    "/static/img/help_images/call_src3.png",
                    "/static/img/help_images/call_src4.png",
                    "/static/img/help_images/call_src5.png",
                    "/static/img/help_images/call_src6.png",
                    "/static/img/help_images/call_src7.png",
                    "/static/img/help_images/call_src8.png",
                ]
            },
            "company" : {
                "title" : [
                    "상호명", "대표자명", "업종", "거래처 전화", "사장 전화", "간단주소", "상세주소", "점수", "비고"
                ],
                "description" : [
                    "\
                    등록할 거래처의 상호명을 입력해주세요. \n 거래처 등록을 하지 않은 거래처를 등록할 경우,\n 거래처 관리를 통한 조회가 되지 않습니다.\n\n \
                    ",
                    "\
                    거래처의 대표자 이름을 입력해주세요. \n 대표자 이름은 '거래처 관리' 페이지에서는 보이지 않으며, '거래처 관리'에서 해당 업체의 '상호명'을 클릭해서 조회할 수 있습니다.\
                    ",
                    "\
                    해당 거래처의 주 업종을 입력해주세요. \n 업종은 거래처 등록 이후 '거래처 관리' 페이지에서 확인할 수 있습니다.\
                    ",
                    "\
                    거래처에서 사용하는 전화번호를 입력해주세요.\
                    ",
                    "\
                    거래처 대표자의 전화번호를 입력해주세요.\
                    ",
                    "\
                    '거래처 관리' 페이지에서 바로 확인할 수 있도록, 간단한 주소를 입력해주세요. \n 예) 마포 \
                    ",
                    "\
                    정확한 구직자와 거래처의 매칭을 위해 상세한 주소를 입력해주세요.\n 상세주소는 '거래처 관리' 페이지에서는 보이지 않으며, '거래처 관리'에서 해당 업체의 '상호명'을 클릭해서 조회할 수 있습니다.\
                    ",
                    "\
                    해당 거래처를 평가할 점수를 입력해주세요. \n 1~ 100의 숫자를 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    "
                ],
                "source" : [
                    "/static/img/help_images/call_src1.png",
                    "/static/img/help_images/call_src2.png",
                    "/static/img/help_images/call_src3.png",
                    "/static/img/help_images/call_src4.png",
                    "/static/img/help_images/call_src5.png",
                    "/static/img/help_images/call_src6.png",
                    "/static/img/help_images/call_src7.png",
                    "/static/img/help_images/call_src8.png",
                    "/static/img/help_images/call_src9.png"
                ]
            },
            "employee" : {
                "title" : [
                    "성명", "성별", "생년월일", "업종", "전화번호", "희망근무지", "간단주소", "상세주소", "한국어", "점수", "비고", "블랙", "일 주세요 / 일 못가", "가입시작일", "가입금액", "수금", "가입비고"
                ],
                "description" : [
                    "\
                    등록할 거래처의 상호명을 입력해주세요. \n 거래처 등록을 하지 않은 거래처를 등록할 경우,\n 거래처 관리를 통한 조회가 되지 않습니다.\n\n \
                    ",
                    "\
                    거래처의 대표자 이름을 입력해주세요. \n 대표자 이름은 '거래처 관리' 페이지에서는 보이지 않으며, '거래처 관리'에서 해당 업체의 '상호명'을 클릭해서 조회할 수 있습니다.\
                    ",
                    "\
                    해당 거래처의 주 업종을 입력해주세요. \n 업종은 거래처 등록 이후 '거래처 관리' 페이지에서 확인할 수 있습니다.\
                    ",
                    "\
                    거래처에서 사용하는 전화번호를 입력해주세요.\
                    ",
                    "\
                    거래처 대표자의 전화번호를 입력해주세요.\
                    ",
                    "\
                    '거래처 관리' 페이지에서 바로 확인할 수 있도록, 간단한 주소를 입력해주세요. \n 예) 마포 \
                    ",
                    "\
                    정확한 구직자와 거래처의 매칭을 위해 상세한 주소를 입력해주세요.\n 상세주소는 '거래처 관리' 페이지에서는 보이지 않으며, '거래처 관리'에서 해당 업체의 '상호명'을 클릭해서 조회할 수 있습니다.\
                    ",
                    "\
                    해당 거래처를 평가할 점수를 입력해주세요. \n 1~ 100의 숫자를 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    ",
                    "\
                    해당 거래처에 대한 추가적인 사항을 입력해주세요.\
                    "
                ],
                "source" : [
                    "/static/img/help_images/call_src1.png",
                    "/static/img/help_images/call_src2.png",
                    "/static/img/help_images/call_src3.png",
                    "/static/img/help_images/call_src4.png",
                    "/static/img/help_images/call_src5.png",
                    "/static/img/help_images/call_src6.png",
                    "/static/img/help_images/call_src7.png",
                    "/static/img/help_images/call_src8.png",
                    "/static/img/help_images/call_src9.png"
                ]
            }
        }


    }();


});