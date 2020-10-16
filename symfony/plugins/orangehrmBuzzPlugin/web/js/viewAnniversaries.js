$(document).ready(function () {
    $("#rightBarHeadingAnniv").on('click', function () {
        $("#upcomingAnnivMonth").toggle(300);
        $("#upcomingAnnivList").toggle(300);
        $("#mc_componentContainer").hide(300);
        $("#ml_componentContainer").hide(300);
        $("#moreAniversary").toggle();
        $("#lessAniversary").toggle();
        $("#moreCommentLiked").show();
        $("#lessCommentLiked").hide();
        $("#moreBirthday").show();
        $("#lessBirthday").hide();
        $("#morePostLiked").show();
        $("#lessPostLiked").hide();
        if ($("#moreAniversary").is(":visible")) {
            $(this).css("border-radius", "10px");
            $("#rightBarHeadingAnniv .rightBarBody").css("border", "none");
        } else {
            $("#rightBarHeadingBirthday, #rightBarHeadingMl, #rightBarHeadingMc").css("border-radius", "10px");
            $(this).css("border-radius", "10px 10px 0px 0px");
            
            $("#upcomingAnnivList").css("border", "1px solid #dedede");
            $("#rightBarHeadingAnniv > .rightBarBody").css("border", "1px solid #dedede");
        }
    });
});
