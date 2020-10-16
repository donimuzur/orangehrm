$(document).ready(function () {
    $("#rightBarHeadingBirthday").on('click', function () {
        $("#upcomingBirthdayMonth").toggle(300);
        $("#upcomingBirthdayList").toggle(300);
        $("#mc_componentContainer").hide(300);
        $("#ml_componentContainer").hide(300);
        $("#moreBirthday").toggle();
        $("#lessBirthday").toggle();
        $("#moreCommentLiked").show();
        $("#lessCommentLiked").hide();
        $("#moreAniversary").show();
        $("#lessAniversary").hide();
        $("#morePostLiked").show();
        $("#lessPostLiked").hide();
        if ($("#moreBirthday").is(":visible")) {
            $(this).css("border-radius", "10px");
            $("#rightBarHeadingBirthday .rightBarBody").css("border", "none");
        } else {
            $("#rightBarHeadingAnniv, #rightBarHeadingMl, #rightBarHeadingMc").css("border-radius", "10px");
            $(this).css("border-radius", "10px 10px 0px 0px");
            $("#upcomingBirthdayList").css("border", "1px solid #dedede");
            $("#rightBarHeadingBirthday .rightBarBody").css("border", "1px solid #dedede");
        }
    });
});
