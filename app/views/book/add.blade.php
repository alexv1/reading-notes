<!DOCTYPE html>
<html>
<head>
    @include('head-meta')
    <title>添书</title>
    @include('head-js')
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
                    <a href="../book/my_list">我的书架</a>
                </li>
                <li class="active">添书</li>
            </ul>
        </div>

        <div class="wrapper">
            @include('nav-my')
            <form id="form" action="../book/add" class="form-horizontal" method="post">
                <div class="directory-info-row">
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="compose-btn pull-right">
                                    <a href="javascript:history.back(-1)" class="btn btn-success btn-sm">返回</a>
                                </div>
                                <div class="btn-toolbar">
                                    <h4 class="pull-left">
                                        添书
                                    </h4>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>书名：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="bname" name="bname" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>副标题：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="subtitle" name="subtitle" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>分类：</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="sid" name="sid" class="form-control" onchange="showTids()" required>
                                            <option value="">请选择分类</option>
                                            @foreach($second_category as $s)
                                                <option value="{{$s->id}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                        <select id="tid" name="tid" class="form-control" required>
                                            <option value="">请选择上级分类</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>作者：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="author" name="author" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>出版社：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="publisher" name="publisher" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>图片：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="pic_url" name="pic_url" class="form-control"/>
                                    </div>
                                </div>
                                <div id="container" class="form-group">
                                    <label for="inputFile" class="col-sm-2 control-label">附件：</label>
                                    <div class="col-sm-4">
                                        <div id="thumbnail" class="fileupload-new thumbnail" style="display:none">
                                            <img id="img_preview" src="{{$path}}images/no-image.png" width="200">
                                        </div>
                                        <input type="button" id="pickpic" class="btn btn-block btn-success" value="选择文件"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>豆瓣ID：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="dou_id" name="dou_id" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>豆瓣评分：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="dou_rate" name="dou_rate" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        <span>外链：</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="share_url" name="share_url" class="form-control" />
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
                </div>
            </form>
        </div>


        @include('footer')
    </div>
</section>

@include('foot-js')
<script type="text/javascript" src="//cdn.bootcss.com/plupload/2.1.9/plupload.full.min.js"></script>
<script type="text/javascript" src="{{$path}}js/qiniu/qiniu.js"></script>
<script>

    var server = "http://7xsyz0.com1.z0.glb.clouddn.com/";

    $(document).ready(function() {
        $('#nav-my').addClass("nav-active");
        $('#menu-book-my').addClass("active");

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
                    }
                    $('#pic_url').val(server + file.name);
                },
                'Error': function(up, err, errTip) {
                }
            }
        });

        $("#form").validate({
            submitHandler:function(form){

                form.submit();
            }
        });

    });

    function showTids(){
        var sid = $("#sid").val();
        $.get(
            "../api/book/action/getTidBySidInJson",
            {
                sid: sid
            },
            function (data) {
                var tmp = JSON.parse(data);
                console.log(tmp);
                if (tmp.data.length > 0) {
                    var inputs = '';
                    $.each(tmp.data, function (idx, obj) {
                        inputs += '<option value="' + obj.id + '">' + obj.name + '</option>';
                    });
                    $("#tid").html(inputs);
                } else {
                    console.log("error tids");
                }
            });
    }

</script>
</body>
</html>