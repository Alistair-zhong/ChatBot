<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css" />
    <script src="/bootstrap/js/jquery.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"> --}}
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <title>测试谷歌云端上传文件</title>
</head>
<body>
    <div class="container">

        <div class="row col-12">
            <form enctype="multipart/form-data" action="{{ route('google.uploads') }}" method="POST">
                <div class="form-group">
                    <label for="upfile">Example file input</label>
                    <input type="file" class="form-control-file" id="upfile" name="upfile">
                </div>
                @csrf
                <div class="form-group">
                    <input type="submit" value="上传" class="btn btn-md btn-info">
                </div>
            </form>
        </div>
        <div class="row col-12">
            <div class="btn btn-waring" onclick="{{ route('google.download') }}">下载</div>
        </div>
        <div class="row col-12">
            <div class="btn btn-waring" id="showBtn">魔术点击</div>
        </div>
        <img src="#" id='imgG'>


    </div>



    <script>

        var showBtn = $('#showBtn');
        var image = document.getElementById('imgG');

        showBtn.click(function(){
            $.ajax({
                method: 'get',
                url: "{{ route('google.show_image') }}",
                success: function(res){
                    // 生成img标签 插入到按钮后
                    image.src = res;
                    console.log(res);
                },
                error: function(res){
                    // 生成h4标签显示错误信息
                    console.log(res);
                }
            })
        })

    </script>
</body>
</html>