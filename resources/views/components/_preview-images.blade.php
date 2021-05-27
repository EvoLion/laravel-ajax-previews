@foreach ($preview_images as $key => $img)
    <div class="col-md-12 mb-2">
        <img id="preview-image-before-upload" src="{{ $img }}" alt="preview image" style="max-height: 250px;">
        <button type="button" class="btn btn-primary delete-preview" id="{{ $key }}">Delete</button>
    </div>
@endforeach
