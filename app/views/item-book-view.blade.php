<h4>{{toHtmlLimit($book->bname, 20)}}
    @if(!empty($book->subtitle))
        <span class="text-muted small"> ：{{toHtmlLimit($book->subtitle, 20 - 1.3 * mb_strlen($book->bname))}}</span>
    @endif
</h4>
<div class="media">
    <a class="pull-left" href="../book/my_view?bid={{$book->bid}}">
        @if(empty($book->pic_url))
            <img src="{{$path}}images/no-image.png" width="90">
        @else
            <img src="{{$book->pic_url}}" width="90">
        @endif
    </a>
    <input type="hidden" id="star_val_{{$book->bid}}" value="{{$book->star}}">
    <div class="media-body">
        <address>
            <strong>{{toHtmlLimit($book->author,20)}}</strong><br>
            <div id="star_view_{{$book->bid}}"></div><br>
            <strong>
                @include('part-category')
            </strong><br>
            标签：{{tagToHtmlLimit($book->tags, 2)}}<br>
            @if($book->read_status == 2)
                {{formatDayFromStr($book->done_time)}}
            @else
                {{formatDayFromStr($book->update_time)}}
            @endif
            &nbsp;&nbsp;{{$read_status_array[$book->read_status]}}
            &nbsp;&nbsp;{{$source_array[$book->source]}}<br>
        </address>
        <a href="#myModal-{{$book->bid}}" data-toggle="modal" class="btn btn-xs btn-info">
            更新
        </a>
        <a href="../book/edit_view?bid={{$book->bid}}" target="_blank" class="btn btn-xs btn-info">编辑</a>
        <a href="{{$book->share_url}}/" target="_blank" class="btn btn-xs btn-info">
            外链传送门
        </a>
    </div>
</div>

