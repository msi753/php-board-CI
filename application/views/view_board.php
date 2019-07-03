<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>뷰 화면</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- 페이스북 공유하기 -->
    <meta property="og:url"           content="/view_board.php?no=<?=$NO?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Your Website Title" />
    <meta property="og:description"   content="Your description" />
    <meta property="og:image"         content="<?=$PATH?>" /> 
    
    <!-- cropper.js 사용하기 위해 추가 -->
    <link  href="/dist/cropper.css" rel="stylesheet">
    <script src="/dist/cropper.js"></script>

    <script>
        $(document).ready(function(){
            //btn_add버튼을 클릭하면 list-group-item의 길이를 받는다
            $("#btn_add").click(function(){
                //현재 출력 중인 댓글의 수를 센다
                var cnt = $(".list-group-item").length;
                
                // $.get( "ajax.php", { NO: 110, cnt: cnt } )
                // .done(function( data ) {
                //     $(".list-group").append(data);

                $.ajax({
                    method: "get",
                    url: "ViewBoard/addBtn",
                    data: { NO: <?=$NO?>, cnt: cnt }
                    }).done(function(msg) {
                        //alert(msg);
                        $("#addComment").append(msg);
                    });
            });
                
            //삭제 버튼
            //on("click", function(){}) 이런 식으로 하면 '동적'으로 변하는 개체에도 사용가능
            $(".list-group-item").on("click", ".btn-danger", function(){
                var sno = $(this).data('sno');
                var $this = $(this);
                //alert(sno);

                $.ajax({
                    method: "get",
                    url: "ViewBoard/deleteComment",
                    data: { NO: sno}
                    })
                    .done(function( msg ) {
                        //alert(msg);
                        if(msg==1) {
                            $this.closest(".list-group-item").remove();
                            alert("삭제되었습니다");
                        } else {
                            alert("삭제 실패");
                        }
                     
                    });

            });

        });

    </script>
</head>
<body>
    <div class="container">
        <h1>게시글 뷰</h1>
        
        <!-- 뷰 상세보기 -->
        <table class="table">
            <tr>
                <th>NO: </th>
                <td><?=$NO?></td>
            <tr>
                <th>조회수: </th>
                <td><?=$HIT?></td>
            </tr>
            <tr>
                <th>IP: </th>
                <td><?=$IP?></td>
            </tr>
            <tr>
                <th>제목: </th>
                <td><?=htmlspecialchars($TITLE)?></td>
            </tr>
            <tr>
                <th>내용: </th>
                <td><?=htmlspecialchars($CONTENTS)?></td>
            </tr>
            <tr>
                <th>글쓴이: </th>
                <td><?=htmlspecialchars($WRITER)?></td>
            </tr>
            <tr>
                <th>날짜: </th>
                <td><?=$DATE?></td>
            </tr>
            <tr>
                <th>이미지: </th>
                <td><img src=<?=$PATH?> width=200><br>
                <?=$IMAGE_NAME?></td>
            </tr> 
        </table>

        <!-- 이미지 크롭 -->
        <div>
            <img id="crop_image" src=<?=$PATH?> style="max-width:100%">
            
            <div>
                <form action="ImgCrop" method="POST">
                    <input type="hidden" name="path" value=<?=$PATH?>>
                    <input type="hidden" name="image_name" value=<?=$IMAGE_NAME?>>
                    <input type="hidden" name="image_id" value=<?=$IMAGE_ID?>>
                    <input type="text" name="x" id="x">
                    <input type="text" name="y" id="y">
                    <input type="text" name="width" id="width">
                    <input type="text" name="height" id="height">
                    <input type="text" name="scaleX" id="scaleX">
                    <input type="text" name="scaleY" id="scaleY">
                    <input type="text" name="zoom" id="zoom">
                    <input type="text" name="rotate" id="rotate">
                    <br>
                    <button type="submit" class="btn btn-primary">크롭하기</button>
                </form>
                <div class="crop_button">
                    <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" disabled="disabled">이동</button>
                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1">확대</button>
                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1">축소</button>
                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90">회전(좌)</button>
                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="90">회전(우)</button>
                    <button type="button" class="btn btn-primary" data-method="reset">초기화</button>
                    <button type="button" class="btn btn-primary" data-method="getCanvasData" disabled="disabled">이미지 정보</button>
                </div>
            </div>           

            <script>
                const image = document.getElementById('crop_image');

                const cropper = new Cropper(image, {
                    aspectRatio: 16 / 9,
                    crop(event) {
                        $('#x').val(Math.floor(event.detail.x));
                        $('#y').val(Math.floor(event.detail.y));
                        $('#width').val(Math.floor(event.detail.width));
                        $('#height').val(Math.floor(event.detail.height));
                        $('#scaleX').val(event.detail.scaleX);
                        $('#scaleY').val(event.detail.scaleY);
                        $('#rotate').val(event.detail.rotate);
                        $('#zoom').val(event.detail.zoom);
                    },
                });

                /* 버튼 이벤트 처리 */
                $('.crop_button button').click(function() {
                    var $this	=	$(this);
                    var data	=	$this.data();   //crop_button클래스에서 버튼의 값 예를 들어 {method:rotate, option:0.1}을 받는다
                    if(data.method=="rotate") {
                        cropper.rotate(data.option);
                    } else if(data.method=="zoom") {
                        cropper.zoom(data.option);
                    /*페이지 초기화일 경우 이미지 실물 크기로*/
                    } else if(data.method == "reset") {
                     	cropper.zoomTo(1);
                    }
                });
     
            </script>

        </div>
        <!--// 이미지 크롭 -->

        <!-- 댓글 폼 -->
        <div class="jumbotron">
            <?php if(is_array($LOOP)):?>
                <?php foreach($LOOP as $row):?>
                <li class="list-group-item">
                    <div class="float-right">
                        <a href="javascript:;" class="btn btn-danger" data-sno="<?=$row['NO']?>">삭제</a>
                    </div>
                    <?=$row['WRITER']?>
                    <?=$row['DATE']?>
                    <div class="clear"></div>
                    <?=$row['CONTENTS']?>
                </li>
                <?php endforeach;?>
            <?php endif;?>

            <div id="addComment"></div>

            <!-- 더보기 버튼 ajax -->
            <button type="button" class="btn btn-primary" id="btn_add">+더보기</button>

            <hr>

            <form action="/ViewBoard/save" method="post">
                <input type="hidden" name="board_no" value="<?=$NO?>">
                <textarea name="comment" class="form-control"></textarea>
                <input type="submit" class="btn btn-primary" value="댓글 저장">
            </form>
        </div>

        <a href="/ListBoard" class="btn btn-primary" role="button">목록으로 돌아가기</a>
        <a href="/AddBoard?no=<?=$NO?>" class="btn btn-primary" role="button">수정</a>
        <a href="/DeleteBoard?no=<?=$NO?>" class="btn btn-primary">삭제</a>

        <!-- JavaScript를 위한 Facebook SDK 불러오기    -->
        <div id="fb-root"></div>
        <script>
            function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v3.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>   
        
        <br>
        <!-- 좋아요 버튼 -->
        <div class="fb-like" 
            data-href="/view_board.php?no=<?=$NO?>"
            data-layout="standard" 
            data-action="like" 
            data-show-faces="true"
            data-share="true">
        </div>

    </div>
</body>
</html>