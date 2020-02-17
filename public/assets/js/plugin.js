var preMainImg = "";
    function changeHoverImg(data) {
        preMainImg = $("#main-img").attr('src');
        $("#main-img").attr("src", data);
    }

    function changeHoverOutImg() {
        $("#main-img").attr("src", preMainImg);
    }

    function changeImgFunc(data, index) {
        
        $("#main-img").attr("src", data);
        preMainImg = $("#main-img").attr('src');

        $(".images").each(function(i) {
            if(index == i) {
                $("#thum-img-" + index).removeClass("border-grey").addClass("border-orange");
            }
            else if(index != i) {
                $("#thum-img-" + i).removeClass("border-orange").addClass("border-grey");
            }
        });
    }