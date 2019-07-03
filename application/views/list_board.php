<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">    
    <!-- 아이콘 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" 
    integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- jQuery를 사용하기 위해 cdn 추가 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <title>boardList</title>

    <script>
        function search_ck(){
            //무슨 내용을 추가해야되지?
            return false;
        }
    </script>
</head>
<body>
    <form action="Login/logout"> 
        <button type="submit" class="btn btn-primary">로그아웃</button>
    </form>
    <div class="container">
        <h1>게시글 목록</h1>

        <!-- 내비게이션 바 -->
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
            <!-- 브랜드/로고 -->
            <a class="navbar-brand" href="/ListBoard">
                <img src="\se2\img\logo.png" alt="logo" style="width:100px;">
            </a>           
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="Map">지도</a>
                </li>
            </ul>
        </nav> 
        <br>
        <form action="/ListBoard" method="get"> <!-- onsubmit="return search_ck();" onsubmit 유효성검사 추가해야하는데 뭘 추가해야하지? -->
            <select name="category">
                <option value="TITLE">제목</option>
                <option value="CONTENTS">내용</option>
            </select>
            <input type="text" name="keyword" value="" title="검색">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>      
        </form>  
        <table class="table table-striped">   
            <tr>
                <td>NO</td>
                <td>제목</td>
                <td>글쓴이</td>
                <td>날짜</td>
                <td>조회수</td>
            </tr>               
            <tr>
            <?php if(count($LOOP)>0):?>
            <?php foreach($LOOP as $row):?>
                <td><?=$row['NO']?></td>
                <td><a href="/ViewBoard?no=<?=$row['NO']?>"><?=htmlspecialchars($row['TITLE'])?></a>[<?=($row['COMMENT_CNT'])?>]</td>
                <td><?=htmlspecialchars($row['WRITER'])?></td>
                <td><?=$row['DATE']?></td>
                <td><?=$row['HIT']?></td>   
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </table>     
            <ul class="pagination">
            <?=$pagenav?>
            </ul>
        <a href="/AddBoard" class="btn btn-primary" role="button">게시글 입력</a>
    </div>


</body>
</html>