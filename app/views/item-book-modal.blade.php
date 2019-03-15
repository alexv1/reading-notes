<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" id="myModal-{{$book->bid}}" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">修改 - {{$book->bname}}
                    @if(!empty($book->subtitle))
                    ：{{$book->subtitle}}
                    @endif</h4>
            </div>
            <div class="modal-body">
                <form id="form_{{$book->bid}}" class="form-horizontal" role="form">
                    <input type="hidden" id="star_{{$book->bid}}" value="{{$book->star}}">
                    <input type="hidden" id="tid_val_{{$book->bid}}" value="{{$book->tid}}">
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">阅读状态</label>
                        <div class="col-lg-10">
                            @foreach($read_status_options as $option)
                                <label class="radio-inline">
                                @if ($option[0] == $book->read_status)
                                    <input type="radio" name="read_status_{{$book->bid}}" value="{{$option[0]}}" checked="checked">{{$option[1]}}
                                @else
                                    <input type="radio" name="read_status_{{$book->bid}}" value="{{$option[0]}}">{{$option[1]}}
                                @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">评分</label>
                        <div class="col-lg-10">
                            <div id="star_edit_{{$book->bid}}"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">
                            <span>分类：</span>
                        </label>
                        <div class="col-lg-4">
                            <select id="sid_{{$book->bid}}" class="form-control" onchange="showTids({{$book->bid}})" required>
                                <option value="">请选择分类</option>
                                @foreach($second_category as $c)
                                    @if ($c->id == $book->sid)
                                        <option value="{{$c->id}}" selected="selected">{{$c->name}}</option>
                                    @else
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select id="tid_{{$book->bid}}" class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">标签</label>
                        <div class="col-lg-10">
                            <input id="tags_input_{{$book->bid}}" type="text" class="tags" value="{{tagToInput($book->tags)}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">标题</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="title" id="title_{{$book->bid}}" value="{{$book->c_title}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">阅读来源</label>
                        <div class="col-lg-5">
                            <select id="source_{{$book->bid}}" class="form-control" required>
                                @foreach($source_options as $option)
                                    @if($option[0]==$book->source)
                                        <option value="{{$option[0]}}" selected="selected">{{$option[1]}}</option>
                                    @else
                                        <option value="{{$option[0]}}">{{$option[1]}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">评价</label>
                        <div class="col-lg-10">
                            <textarea rows="6" class="form-control" name="content" id="content_{{$book->bid}}">{{$book->c_content}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="button" class="btn btn-primary" onclick="updateBook({{$book->bid}})">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>