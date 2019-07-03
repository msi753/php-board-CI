<!DOCTYPE html>  
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>게시글 입력</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
	//유효성 검사(공백(spacebar)에 대한 내용 추가)
	$(document).ready(function(){
		$('#addBtn').click(function(){	
			$('#addBoard').submit(function(event){
                var pwPattern = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;

				if($.trim($('#title').val()).length<1) {
					$('#title').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;	
				} else if($.trim($('#contents').val()).length<1) {      //에디터를 쓴 이후 공백일 경우 값(<p><br></p>)이 들어간다 
					$('#contents').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;
				} else if(!pwPattern.test($('#pw').val())) {
					$('#pw').attr('placeholder', '비밀번호는 영문, 숫자 혼합하여 6~20자리 이내로 입력해 주세요.').val('').focus();
					return false;
				} else if($.trim($('#writer').val()) == '') {
					$('#writer').attr('placeholder', '한 글자 이상 입력해 주세요.').val('').focus();
					return false;
				}
			});
		});
	});
    </script>
    
</head>
    <body>

        <div class="container">
        <h1>게시글 입력</h1>
            <form action="/AddBoard/save" method="post" id="addBoard" enctype="multipart/form-data" onsubmit="submitContents()">
            <input type="hidden" name="no" value="<?=$NO?>">
            <div class="form-group">
                글 제목:
                <input type="text" name="title" class="form-control" id="title" value='<?=$TITLE?>'>
            </div>
            <div class="form-group">
                내용:
                <textarea name="contents" id="contents" rows="10" cols="100" class="form-control" ><?=$CONTENTS?></textarea>
            </div>
            <div class="form-group">
                비밀번호:
                <input type="password" name="pw" class="form-control" id="pw" value="<?=$PW?>">
            </div>
            <div class="form-group">   <!-- 세션에 있는 값을 받아서 입력하고 싶은데 고칠 게 많다 -->
                글쓴이:
                <input type="text" name="writer" class="form-control" id="writer" value="<?=$WRITER?>">
            </div> 
            <div class="form-group">
                파일 업로드:
                <input type="hidden" name="MAX_FILE_SIZE" value="100000000000000000000000" />   <!-- 보안상 별로 도움 안됨 -->

                <input type="file" name="userfile"/>
                업로드된 파일: <?php if($IMAGE_ID!=''){ ?>
                    <img src=<?=$PATH?> width=200><br> <?=$IMAGE_NAME?>
                <?php } else { ?>
                    <?php echo "업로드된 파일이 없습니다."; 
                }?>
            </div>        
            <button type="submit" class="btn btn-primary" id="addBtn" name="submit">글 작성</button>
            <input type="reset" class="btn btn-primary" value="초기화">
            </form>
            <br>
            <a href="/ListBoard" class="btn btn-primary" role="button">게시글 목록</a>
        </div>

        <!-- 에디터 사용을 위한 스크립트 -->
        <script type="text/javascript" src="../se2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
        <!-- 에디터 생성 -->
        <script type="text/javascript">
            var oEditors = [];
            nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditors,
            elPlaceHolder: "contents",
            sSkinURI: "../se2/SmartEditor2Skin.html",
            fCreator: "createSEditor2"
            });
        </script>
        <script>
            // ‘저장’ 버튼을 누르는 등 저장을 위한 액션을 했을 때 submitContents가 호출된다고 가정한다.
            function submitContents(elClickedObj) {
                // 에디터의 내용이 textarea에 적용된다.
                oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
                // 에디터의 내용에 대한 값 검증은 이곳에서
                document.getElementById("contents").value; //를 이용해서 처리한다.
                elClickedObj.form.submit();
            }
        </script>
<div class="jFiler jFiler-theme-dragdropbox">
    <input type="file" name="files[]" id="filer_default" multiple="multiple" style="position: absolute; left: -9999px; top: -9999px; z-index: -9999;">
    <div class="jFiler-input-dragDrop">
        <div class="jFiler-input-inner">
            <div class="jFiler-input-icon">
                <i class="icon-jfi-cloud-up-o"></i>
            </div>
            <div class="jFiler-input-text">
                <h3>Drag&amp;Drop files here</h3>
                 <span style="display:inline-block; margin: 15px 0">or</span>
            </div>
            <a class="jFiler-input-choose-btn blue">Browse Files</a>
        </div>
    </div>
</div>
    </body>
</html>