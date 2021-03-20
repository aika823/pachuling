// Form toggle 열고 닫기 버튼
$('.icon-up').on("click", function(){
    $(this).closest('.row-in-row').find('.row-content').toggle(function(){
    });
    $(this).closest('.icon-up').find('.fas').attr("class") === "fas fa-chevron-up"
    ? $(this).closest('.icon-up').find('.fas').attr("class", "fas fa-chevron-down")
    : $(this).closest('.icon-up').find('.fas').attr("class", "fas fa-chevron-up")
});