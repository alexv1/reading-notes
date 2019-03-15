<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>添加笔记</title>
    @include('head-js')
    {{--<link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/bootstrap-markdown/2.10.0/css/bootstrap-markdown.min.css" />--}}
</head>

<body class="sticky-header">

<section>
    @include('left-side')
    <div class="main-content" >
        @include('header')
        <div class="page-heading">
            <ul class="breadcrumb">
                <li>
                    <a href="../dashboard/index">首页</a>
                </li>
                <li>
                    <a href="../note/my_list">我的笔记</a>
                </li>
                <li class="active">添加笔记</li>
            </ul>
        </div>

        <div class="wrapper">
            <form id="form" action="../note/add" class="form-horizontal" method="post">
                <input type="hidden" name="bid" id="bid" value="{{$data->bid}}">
                <input type="hidden" name="did" id="did" value="">
                @if(!empty($draft))
                    <input type="hidden" id="draft" value="{{{$draft->content}}}">
                @endif
                <input type="hidden" name="file_url" id="file_url" value="">
                <input type="hidden" name="file_type" id="file_type" value="">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="compose-btn pull-right">
                                <a href="../note/my_list" class="btn btn-success btn-sm">笔记列表</a>
                            </div>
                            <div class="btn-toolbar">
                                <h4 class="pull-left">
                                    添加笔记
                                </h4>
                            </div>
                        </header>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span>图书：</span>
                                </label>
                                <div class="col-sm-6">
                                    <p class="form-control-static">
                                        <a href="../book/my_view?bid={{$data->bid}}">{{$data->bname}}</a>&nbsp;&nbsp;&nbsp;{{$data->author}}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span>标题：</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" id="title" name="title" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span>外链：</span>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" id="share_url" name="share_url" class="form-control"/>
                                </div>
                            </div>
                            <div id="container" class="form-group">
                                <label for="inputFile" class="col-sm-2 control-label">附件</label>
                                <div class="col-sm-4">
                                    <div id="thumbnail" class="fileupload-new thumbnail" style="display:none">
                                        <img id="img_preview" src="{{$path}}images/no-image.png" width="200">
                                    </div>
                                    <label id="audio" class="control-label" style="display:none"></label>
                                    <input type="button" id="pickpic" class="btn btn-block btn-success" value="选择文件"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span>摘要：</span>
                                </label>
                                <div class="col-sm-6">
                                    <textarea id="abstract" name="abstract" rows="4" class="form-control"></textarea>
                                </div>
                            </div>
                            @if(!empty($draft))
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>未保存草稿</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="button" class="btn btn-block btn-info" value="导入" onclick="loadDraft()"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    <span>笔记：</span>
                                </label>
                                <div class="col-sm-6">
                                    <textarea id="content" name="content" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 control-label">
                                </div>
                                <div class="col-sm-4 control-label">
                                    <input type="submit" class="btn btn-block btn-success" value="保存"/>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            </form>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="//cdn.bootcss.com/plupload/2.1.9/plupload.full.min.js"></script>
<script type="text/javascript" src="{{$path}}js/qiniu/qiniu.js"></script>
{{--<script type="text/javascript" src="http://cdn.bootcss.com/bootstrap-markdown/2.10.0/js/bootstrap-markdown.min.js"></script>--}}
<script>

    var server = "http://7xsyz0.com1.z0.glb.clouddn.com/";
    var draftId = $('#bid').val()  + "_"+ (Date.parse(new Date())/1000).toString();
    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-note-my').addClass("active");

        var uploader = Qiniu.uploader({
            runtimes: 'html5,flash,html4',
            browse_button: 'pickpic',
            container: 'container',
            drop_element: 'container',
            filters: {
                mime_types : [ //只允许上传图片,音频
                    { title : "Image files", extensions : "jpg,png" },
                    { title : "Audio files", extensions : "mp3" }
                ],
                max_file_size : '10mb', //最大只能上传10mb的文件
                prevent_duplicates : true //不允许选取重复文件
            },
            flash_swf_url: 'http://libs.cdnjs.net/files/plupload/2.1.1/Moxie.swf',
            dragdrop: true,
            chunk_size: '4mb',
            uptoken_url: '../uploadToken',
            domain: '7xsyz0.com1.z0.glb.clouddn.com',
            get_new_uptoken: false,
            auto_start: true,
            log_level: 5,
            init: {
                'FilesAdded': function(up, files) {
                    plupload.each(files, function(file) {
                        var timestamp = Date.parse(new Date());
                        file.name = timestamp + '_'+ file.name;
                    });
                },
                'BeforeUpload': function(up, file) {
                },
                'UploadProgress': function(up, file) {
                },
                'UploadComplete': function() {

                },
                'FileUploaded': function(up, file, info) {
                    if(file.type=='image/jpeg' || file.type=='image/png'){
                        $('#thumbnail').show();
                        $('#img_preview').attr('src', server + file.name);
                        $('#file_type').val('0');
                    } else {
                        $('#audio').show();
                        $('#audio').html(file.name);
                        $('#file_type').val('1');
                    }
                    $('#file_url').val(server + file.name);
                },
                'Error': function(up, err, errTip) {
                }
            }
        });


        $("#form").validate({
            submitHandler:function(form){
                $('#did').val(draftId);
                form.submit();
            }
        });

        setInterval("saveNoteDraft()" , 30000 );
    });

    function saveNoteDraft(){
        var content = $('#content').val();
        if(content == null || ""==content){
            return;
        }
        $.post(
            "../note/draft_save",
            {
                did: draftId,
                bid: $('#bid').val(),
                content: $('#content').val()
            },
            function (data) {
                var tmp = JSON.parse(data);
                if (tmp.code == 0) {
                    console.log("saveNoteDraft success#" + draftId);
                }
            });
    }

    function loadDraft(){
        var draft = $('#draft').val()
        $('#content').val(draft);
    }
</script>
</body>
</html>