@if($book->sid!=0)
    <a href="../search/category_2?sid={{$book->sid}}" target="_blank">{{$book->sname}}</a> -
    <a href="../search/category_3?tid={{$book->tid}}" target="_blank">{{$book->tname}}</a>
@else
    {{$book->sname}}
@endif