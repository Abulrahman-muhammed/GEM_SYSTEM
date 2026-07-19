<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

<meta charset="UTF-8">

<title>Membership Card</title>

<style>

@page{
    size:85.6mm 54mm;
    margin:0;
}

body{
    margin:0;
    font-family:Arial,sans-serif;
    background:#eee;
}

.card{

    width:85.6mm;
    height:54mm;

    margin:auto;

    background:white;

    border-radius:10px;

    overflow:hidden;

    border:1px solid #ddd;

    padding:8px;

    box-sizing:border-box;

}

.header{

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:8px;

}

.logo{

    font-size:18px;
    font-weight:bold;

}

.member{

    display:flex;
    align-items:center;
}

.member img{

    width:55px;
    height:55px;

    border-radius:50%;
    object-fit:cover;

    margin-left:10px;

}

.info{

    font-size:11px;
}

.info p{

    margin:2px 0;

}

.barcode{

    margin-top:10px;
    text-align:center;

}

.barcode svg{

    width:100%;
    height:40px;

}

.code{

    font-size:11px;
    letter-spacing:2px;
    font-weight:bold;

}

.actions{

    text-align:center;
    margin:20px;

}

@media print{

.actions{
display:none;
}

body{
background:white;
}

}

</style>

</head>

<body>

<div class="actions">
    <button onclick="window.print()">
        🖨️ طباعة
    </button>
</div>

<div class="card">

    <div class="header align-center">
        <strong>Membership Card</strong>
    </div>


    <div class="barcode">

        {!! $barcode !!}

        <div class="code">
            {{ $member->barcode }}
        </div>

    </div>

</div>

<script>

window.onload=function(){

    setTimeout(function(){

        window.print();

    },300);

}

</script>

</body>

</html>