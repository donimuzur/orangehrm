$(document).ready(function () {
    $("#rightBarHeadingMc").on('click', function () {
        $("#upcomingAnnivMonth").hide(300);
        $("#upcomingAnnivList").hide(300);
        $("#mc_componentContainer").toggle(300);
        $("#ml_componentContainer").hide(300);
        $("#morePostLiked").show();
        $("#lessPostLiked").hide();
        $("#moreCommentLiked").toggle();
        $("#lessCommentLiked").toggle();
        $("#moreBirthday").show();
        $("#lessBirthday").hide();
        $("#moreAniversary").show();
        $("#lessAniversary").hide();
        if ($("#moreCommentLiked").is(":visible")) {
            $(this).css("border-radius", "10px");
            $("#mc_componentContainer").css("border", "none");
        } else {
            $("#rightBarHeadingMl, #rightBarHeadingAnniv, #rightBarHeadingBirthday").css("border-radius", "10px");
            $(this).css("border-radius", "10px 10px 0px 0px");
            $("#mc_componentContainer").css("border", "1px solid #dedede");
            $(".rightBarBody").css("border", "none");
        }
    });

    $("#rightBarHeadingMl").on('click', function () {
        $("#upcomingAnnivMonth").hide(300);
        $("#upcomingAnnivList").hide(300);
        $("#mc_componentContainer").hide(300);
        $("#ml_componentContainer").toggle(300);
        $("#morePostLiked").toggle();
        $("#lessPostLiked").toggle();
        $("#moreCommentLiked").show();
        $("#lessCommentLiked").hide();
        $("#moreAniversary").show();
        $("#lessAniversary").hide();
        $("#moreBirthday").show();
        $("#lessBirthday").hide();
        if ($("#morePostLiked").is(":visible")) {
            $(this).css("border-radius", "10px");
            $("#ml_componentContainer").css("border", "none");
        } else {
            $("#rightBarHeadingMl, #rightBarHeadingAnniv, #rightBarHeadingBirthday, #rightBarHeadingMc").css("border-radius", "10px");
            $(this).css("border-radius", "10px 10px 0px 0px");
            $("#ml_componentContainer").css("border", "1px solid #dedede");
            $(".rightBarBody").css("border", "none");
        }
    });
});
