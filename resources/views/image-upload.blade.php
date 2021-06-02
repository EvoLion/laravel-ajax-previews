<!DOCTYPE html>
<html>
    <head>
        <title>Laravel 8 Image Upload With Preview</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container mt-4">
            <h2 class="text-center">Image Upload with Preview using in Laravel 8</h2>
            <form method="POST" enctype="multipart/form-data" id="upload-image" action="{{ url('upload-image') }}" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="file" multiple name="images[]" placeholder="Choose image" id="images" accept="image/*">
                                @error('images')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>

                    <div id='preview-files'>
                        <div class="col-md-12 mb-2">          
                            <img id="preview-image-before-upload" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif" alt="preview image" style="max-height: 250px;">
                        </div>
                    </div>
                    

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </div>
                </div>     
            </form>
        </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    /**
     *  Simple JavaScript Promise that reads a file as text.
     **/
    function readFileAsText(file){
        return new Promise(function(resolve,reject){
            let fr = new FileReader();

            fr.onload = function(){
                resolve(fr.result);
            };

            fr.onerror = function(){
                reject(fr);
            };

            fr.readAsDataURL(file);
        });
    }

    // Handle multiple fileuploads
    document.getElementById("images").addEventListener("change", function(ev){
        $("#images").rules("add", {
            accept: "jpg|jpeg|png|ico|bmp"
        });
        let files = ev.currentTarget.files;
        let readers = [];
        console.log(files);

        // Abort if there were no files selected
        if(!files.length) return;

        // Store promises in array
        for(let i = 0;i < files.length;i++){
            readers.push(readFileAsText(files[i]));
        }
        
        // Trigger Promises
        Promise.all(readers).then((values) => {
            // Values will be an array that contains an item
            // with the text of every selected file
            // ["File1 Content", "File2 Content" ... "FileN Content"]
            
            $.ajax({
                url: 'preview-image',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: { 'previews' : values },
                success: function(data) {
                    renderPrewiews(data);
                },
                error: function(data){
                    alert("ERROR - " + data.responseText);
                }
            });
        });
    });

function renderPrewiews(data) {
    $( '#preview-files' ).html( data );

    $('.delete-preview').click(function() {
        let id_to_delete = this.id;
        deletePreview(id_to_delete);
    });
}

function deletePreview(id_to_delete) {
    $.ajax({
        url: 'delete-image',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: { 'previews_to_delete' :id_to_delete },
        success: function(data) {
            renderPrewiews(data);
        },
        error: function(data){
            alert("ERROR - " + data.responseText);
        }
    });
}

</script>
    </body>
</html>