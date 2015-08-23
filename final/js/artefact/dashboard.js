/**
 * Created by poovarasan on 22/8/15.
 */

$(function () {


    var options = {
        $AutoPlay: false,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
        $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
        $SlideDuration: 600,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
        $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
        $SlideWidth: 250,                                   //[Optional] Width of every slide in pixels, default value is width of 'slides' container
        //$SlideHeight: 150,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
        $SlideSpacing: 3, 					                //[Optional] Space between each slide in pixels, default value is 0
        $DisplayPieces: 4,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
        $ParkingPosition: 0,                              //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
        $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
        $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
        $DragOrientation: 1,
        $Loop: 0,                               //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

        $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
            $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
            $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
            $AutoCenter: 0,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
            $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
            $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
            $SpacingX: 0,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
            $SpacingY: 0,                                   //[Optional] Vertical space between each item in pixel, default value is 0
            $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
        },

        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
            $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
            $AutoCenter: 2,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
            $Steps: 4                                       //[Optional] Steps to go for each navigation request, default value is 1
        }
    };

    var jssor_slider1 = new $JssorSlider$("slider1_container", options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizes
    function ScaleSlider() {
        var bodyWidth = document.body.clientWidth;
        if (bodyWidth)
            jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 1140));
        else
            window.setTimeout(ScaleSlider, 30);
    }

    ScaleSlider();

    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);


    $(".moreinfo").on('click', function () {
        var typeId = $(this).attr("id");
        //alert("fdgfd");
        var urlC = "displayScheduleType.php?artefactType=" + typeId + "&dtype=c";
        $.get(urlC, function (data) {

            //$('#cItem').html("");
            if (data == 'NULL') {
                $('#cItem').html("No Task Today in " + typeId);
            } else {
                $('#cItem').html(data);
            }
        });

        var urlC = "displayScheduleType.php?artefactType=" + typeId + "&dtype=p";
        $.get(urlC, function (data) {

            //$('#cItem').html("");
            if (data == 'NULL') {
                $('#pItem').html("No Pending Task in " + typeId);
            } else {
                $('#pItem').html(data);
            }
        });

        var bg = $(this).css("background-color");
        var pg = $(this).prev().css("background-color");

        $('.pending-task-header').css("background-color", bg);
        $('.recent-entries-header').css("background-color", bg);
        $('.pending-task-footer').css("background-color", bg);
        $('.recent-entries-footer').css("background-color", bg);
        $('.recent-entries-item').css("background", pg);
        $('.pending-task-item').css("background", pg);


        return false;
    });


});

function updateTaskUI(artefactType) {


}