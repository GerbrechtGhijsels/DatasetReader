<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> JSON reader</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
</head>
<body>
<div class="container-fluid">
    <br />
    <h3 align="center"> JSON to Database</h3>
    <br />

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Select JSON file</h3>
        </div>
        <div class="panel-body">
            <form id="dropzoneForm" class="dropzone" action="{{ route('upload') }}">
                @csrf
            </form>
            <div align="center">
                <button type="button" class="btn btn-info" id="submit-all">Upload</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
    Dropzone.options.dropzoneForm = {
        autoProcessQueue : false,
        acceptedFiles : ".json",
        maxFiles: 1,
        init:function(){
            var submitButton = document.querySelector("#submit-all");
            myDropzone = this;
            submitButton.addEventListener('click', function(){
                myDropzone.processQueue();
            });
            this.on("complete", function(){
                if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
                {
                    var _this = this;
                    _this.removeAllFiles();
                }

            });
        }
    };

</script>